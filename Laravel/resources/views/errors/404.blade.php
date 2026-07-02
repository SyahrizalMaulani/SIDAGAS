<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Halaman Tidak Ditemukan - SIDAGAS</title>
  <link rel="preconnect" href="https://rsms.me">
  <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center bg-[#0A192F] text-white antialiased font-sans">
  <div class="relative mx-auto max-w-xl px-6 py-12 text-center" x-data="{ hovered: false }">
    <!-- Decorative Ambient Glow -->
    <div class="absolute -top-20 left-1/2 -z-10 h-72 w-72 -translate-x-1/2 rounded-full bg-[#00E5FF]/10 blur-3xl"></div>
    <div class="absolute -bottom-20 left-1/2 -z-10 h-72 w-72 -translate-x-1/2 rounded-full bg-[#0050A1]/20 blur-3xl"></div>

    <!-- Animated Droplet Visual -->
    <div class="flex justify-center">
      <div class="relative cursor-pointer transition-transform duration-300"
           :class="hovered ? 'scale-110' : 'scale-100'"
           @mouseenter="hovered = true"
           @mouseleave="hovered = false">
        
        <!-- Outer pulse ring -->
        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-[#00E5FF]/20 opacity-75"></span>
        
        <!-- Water Droplet SVG -->
        <svg class="relative h-40 w-40 text-gradient filter drop-shadow-[0_0_15px_rgba(0,229,255,0.4)]" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <defs>
            <linearGradient id="dropletGrad" x1="0%" y1="0%" x2="100%" y2="100%">
              <stop offset="0%" stop-color="#00E5FF" />
              <stop offset="50%" stop-color="#00A2E8" />
              <stop offset="100%" stop-color="#0050A1" />
            </linearGradient>
          </defs>
          <path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z" fill="url(#dropletGrad)" />
        </svg>

        <!-- Dynamic Speech Bubble -->
        <div class="absolute -top-12 left-1/2 -translate-x-1/2 rounded-md bg-[#0050A1] px-3 py-1.5 text-xs font-semibold text-[#00E5FF] shadow-lg transition-opacity duration-300 pointer-events-none whitespace-nowrap"
             x-show="hovered"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-2">
          Aduh, galonnya bocor! 💧
        </div>
      </div>
    </div>

    <!-- Error Text -->
    <h1 class="mt-8 text-8xl font-black tracking-widest text-[#00E5FF] filter drop-shadow-[0_2px_8px_rgba(0,229,255,0.3)]">404</h1>
    <h2 class="mt-4 text-2xl font-bold tracking-tight text-white sm:text-3xl">Halaman Tidak Ditemukan</h2>
    <p class="mt-4 text-base leading-7 text-slate-400">
      Maaf, halaman yang Anda cari tidak dapat kami temukan. Mungkin tautannya rusak, atau halaman telah dipindahkan.
    </p>

    <!-- Navigation Buttons -->
    <div class="mt-10 flex flex-col justify-center gap-4 sm:flex-row">
      <a href="/" class="rounded-md bg-gradient-to-r from-[#00E5FF] via-[#00A2E8] to-[#0050A1] px-6 py-3 text-sm font-bold text-white shadow-lg shadow-[#0050A1]/30 hover:opacity-90 transition">
        Kembali ke Beranda
      </a>
      <a href="/kontak" class="rounded-md border border-[#00E5FF]/40 px-6 py-3 text-sm font-bold text-[#00E5FF] hover:bg-[#00E5FF]/10 transition">
        Bantuan
      </a>
    </div>
  </div>
</body>
</html>
