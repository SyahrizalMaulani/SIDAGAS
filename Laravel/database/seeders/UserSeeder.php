<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\CustomerProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(['email' => 'admin@sidagas.com'], [
            'name' => 'Administrator',
            'phone' => '6285224365449',
            'password' => Hash::make(env('SEED_ADMIN_PASSWORD', 'admin')),
            'role' => 'admin',
            'is_active' => true,
        ]);

        User::updateOrCreate(['email' => 'karyawan@sidagas.com'], [
            'name' => 'Karyawan Pabrik',
            'password' => Hash::make(env('SEED_KARYAWAN_PASSWORD', 'karyawan')),
            'role' => 'karyawan',
            'is_active' => true,
        ]);

        User::updateOrCreate(['email' => 'driver@sidagas.com'], [
            'name' => 'Driver Pengiriman',
            'password' => Hash::make(env('SEED_DRIVER_PASSWORD', 'driver')),
            'role' => 'driver',
            'is_active' => true,
        ]);

        $customer = User::updateOrCreate(['email' => 'pelanggan@sidagas.com'], [
            'name' => 'Pelanggan Setia',
            'phone' => '6280000000000',
            'password' => Hash::make(env('SEED_PELANGGAN_PASSWORD', 'pelanggan')),
            'role' => 'pelanggan',
            'is_active' => true,
        ]);

        CustomerProfile::updateOrCreate(['user_id' => $customer->id], [
            'customer_code' => 'CUST-' . str_pad((string) $customer->id, 4, '0', STR_PAD_LEFT),
            'address' => 'Desa Ilir, Kandanghaur, Indramayu',
            'village' => 'Ilir',
            'district' => 'Kandanghaur',
            'city' => 'Indramayu',
            'province' => 'Jawa Barat',
            'customer_type' => 'rumah_tangga',
        ]);

    }
}
