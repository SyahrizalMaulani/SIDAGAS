<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="description" content="{{ $description }}">
  <meta name="robots" content="index, follow">
  <link rel="canonical" href="{{ url()->current() }}">
  <link rel="preconnect" href="https://rsms.me">
  <link rel="preconnect" href="https://cdn.jsdelivr.net">
  <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <title>{{ $title }}</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @if(!empty($schema))
    <script type="application/ld+json">@json($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)</script>
  @endif
</head>

<body class="min-h-screen bg-white text-[#0A192F] antialiased">
  <x-navbar/>
  <main>
    {{ $slot }}
  </main>

  <a href="https://wa.me/6285224365449?text={{ rawurlencode('Halo Admin PD Syahrizal Galon, saya ingin bertanya tentang layanan SIDAGAS.') }}"
     class="fixed bottom-5 right-5 z-40 inline-flex h-16 w-16 items-center justify-center rounded-full bg-white p-2 shadow-lg shadow-green-900/20 transition hover:scale-105 focus:outline-none focus:ring-4 focus:ring-green-200"
     aria-label="Hubungi admin SIDAGAS via WhatsApp">
    <img src="{{ asset('img/logo/Whatsapp-logo.webp') }}" alt="" width="128" height="128" decoding="async" class="h-full w-full object-contain">
  </a>

  <x-footer/>
</body>
</html>
