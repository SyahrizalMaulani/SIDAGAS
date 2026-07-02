<?php

namespace App\Http\Controllers;

use App\Models\CustomerProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'address' => ['nullable', 'string', 'max:1000'],
            'village' => ['nullable', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'province' => ['nullable', 'string', 'max:100'],
            'customer_type' => ['required', 'in:rumah_tangga,warung,umkm,restoran,mitra'],
        ]);

        $user = DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'password' => Hash::make($validated['password']),
                'role' => 'pelanggan',
                'is_active' => true,
            ]);

            CustomerProfile::create([
                'user_id' => $user->id,
                'customer_code' => 'CUST-' . str_pad((string) $user->id, 4, '0', STR_PAD_LEFT),
                'address' => $validated['address'] ?? null,
                'village' => $validated['village'] ?? null,
                'district' => $validated['district'] ?? null,
                'city' => $validated['city'] ?? null,
                'province' => $validated['province'] ?? null,
                'customer_type' => $validated['customer_type'],
            ]);

            return $user;
        });

        Auth::login($user);

        session([
            'token' => 'local-token-' . uniqid(),
            'role' => $user->role,
            'name' => $user->name,
        ]);

        return redirect('/pelanggan');
    }

    /**
     * Memproses login secara Hybrid:
     * - Cek kredensial ke Database Lokal (Tabel users)
     * - Menyimpan Role di dalam Session untuk proteksi Middleware
     */
    public function login(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Coba login menggunakan Auth bawaan Laravel (Database Lokal 'sidagas')
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'is_active' => true])) {
            
            // Ambil data user yang sedang login
            $user = Auth::user();

            // Simpan Session Role layaknya BFF, agar Middleware sebelumnya tetap jalan
            session([
                'token' => 'local-token-' . uniqid(), // Dummy token untuk menjaga integrasi jika dibutuhkan
                'role' => $user->role,
                'name' => $user->name
            ]);

            // Redirect ke halaman yang sesuai dengan role-nya
            return $this->redirectBasedOnRole($user->role);
        }

        // Jika gagal login
        return back()->withErrors([
            'email' => 'Email atau password salah. Cek kembali akun Anda.',
        ])->withInput();
    }

    /**
     * Logout dan hapus session
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->forget(['token', 'role', 'name']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Helper untuk Routing Otomatis
     */
    private function redirectBasedOnRole($role)
    {
        switch ($role) {
            case 'admin':
                return redirect('/admin');
            case 'karyawan':
                return redirect('/karyawan');
            case 'driver':
                return redirect('/driver');
            case 'pelanggan':
                return redirect('/pelanggan');
            default:
                return redirect('/login');
        }
    }
}
