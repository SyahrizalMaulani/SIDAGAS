<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="robots" content="noindex, nofollow">
  <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
  <title>Daftar Pelanggan SIDAGAS</title>
  @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-[#F7FDFF] text-[#0A192F]">
  <main class="mx-auto max-w-3xl px-6 py-12">
    <div class="rounded-lg bg-white p-8 shadow-sm">
      <a href="{{ route('home') }}" class="text-sm font-bold text-[#0050A1] hover:text-[#0A192F]">Kembali ke beranda</a>
      <h1 class="mt-6 text-3xl font-bold">Daftar Pelanggan</h1>
      <p class="mt-2 text-sm leading-6 text-slate-600">Registrasi publik hanya untuk pelanggan. Akun admin, karyawan, dan driver dibuat oleh admin.</p>

      @if($errors->any())
        <div class="mt-6 rounded-md border border-red-200 bg-red-50 p-4 text-sm text-red-700">
          <ul class="list-disc space-y-1 pl-5">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('register') }}" method="POST" class="mt-8 grid gap-5 md:grid-cols-2">
        @csrf
        <div class="md:col-span-2">
          <label for="name" class="text-sm font-bold">Nama Lengkap</label>
          <input id="name" name="name" value="{{ old('name') }}" required class="mt-2 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#0050A1] focus:outline-none focus:ring-2 focus:ring-[#B8F7FF]">
        </div>
        <div>
          <label for="email" class="text-sm font-bold">Email</label>
          <input id="email" type="email" name="email" value="{{ old('email') }}" required class="mt-2 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#0050A1] focus:outline-none focus:ring-2 focus:ring-[#B8F7FF]">
        </div>
        <div>
          <label for="phone" class="text-sm font-bold">Nomor WhatsApp</label>
          <input id="phone" name="phone" value="{{ old('phone') }}" class="mt-2 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#0050A1] focus:outline-none focus:ring-2 focus:ring-[#B8F7FF]">
        </div>
        <div>
          <label for="password" class="text-sm font-bold">Password</label>
          <input id="password" type="password" name="password" required class="mt-2 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#0050A1] focus:outline-none focus:ring-2 focus:ring-[#B8F7FF]">
        </div>
        <div>
          <label for="password_confirmation" class="text-sm font-bold">Konfirmasi Password</label>
          <input id="password_confirmation" type="password" name="password_confirmation" required class="mt-2 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#0050A1] focus:outline-none focus:ring-2 focus:ring-[#B8F7FF]">
        </div>
        <div class="md:col-span-2">
          <label for="address" class="text-sm font-bold">Alamat</label>
          <textarea id="address" name="address" rows="3" class="mt-2 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#0050A1] focus:outline-none focus:ring-2 focus:ring-[#B8F7FF]">{{ old('address') }}</textarea>
        </div>
        <div>
          <label for="village" class="text-sm font-bold">Desa/Kelurahan</label>
          <input id="village" name="village" value="{{ old('village') }}" class="mt-2 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#0050A1] focus:outline-none focus:ring-2 focus:ring-[#B8F7FF]">
        </div>
        <div>
          <label for="district" class="text-sm font-bold">Kecamatan</label>
          <input id="district" name="district" value="{{ old('district') }}" class="mt-2 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#0050A1] focus:outline-none focus:ring-2 focus:ring-[#B8F7FF]">
        </div>
        <div>
          <label for="city" class="text-sm font-bold">Kabupaten/Kota</label>
          <input id="city" name="city" value="{{ old('city') }}" class="mt-2 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#0050A1] focus:outline-none focus:ring-2 focus:ring-[#B8F7FF]">
        </div>
        <div>
          <label for="province" class="text-sm font-bold">Provinsi</label>
          <input id="province" name="province" value="{{ old('province', 'Jawa Barat') }}" class="mt-2 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#0050A1] focus:outline-none focus:ring-2 focus:ring-[#B8F7FF]">
        </div>
        <div class="md:col-span-2">
          <label for="customer_type" class="text-sm font-bold">Jenis Pelanggan</label>
          <select id="customer_type" name="customer_type" required class="mt-2 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#0050A1] focus:outline-none focus:ring-2 focus:ring-[#B8F7FF]">
            @foreach(['rumah_tangga' => 'Rumah Tangga', 'warung' => 'Warung', 'umkm' => 'UMKM', 'restoran' => 'Restoran', 'mitra' => 'Mitra'] as $value => $label)
              <option value="{{ $value }}" @selected(old('customer_type', 'rumah_tangga') === $value)>{{ $label }}</option>
            @endforeach
          </select>
        </div>
        <div class="flex flex-col gap-3 md:col-span-2 sm:flex-row sm:items-center">
          <button type="submit" class="rounded-md bg-[#0B6B3A] px-5 py-3 text-sm font-bold text-white hover:bg-[#07542D]">Daftar</button>
          <a href="{{ route('login') }}" class="text-sm font-bold text-[#0050A1] hover:text-[#0A192F]">Sudah punya akun? Masuk</a>
        </div>
      </form>
    </div>
  </main>
</body>
</html>
