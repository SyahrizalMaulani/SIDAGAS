<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Akses Ditolak (403) - SIDAGAS</title>
  <link rel="preconnect" href="https://rsms.me">
  <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      20%, 60% { transform: translateX(-6px); }
      40%, 80% { transform: translateX(6px); }
    }
    .shake-anim {
      animation: shake 0.4s ease-in-out;
    }
  </style>
</head>
<body class="flex min-h-screen items-center justify-center bg-[#0A192F] text-white antialiased font-sans">
  <div class="relative mx-auto max-w-xl px-6 py-12 text-center" x-data="{ shake: false, clickCount: 0 }">
    <!-- Decorative Ambient Glow -->
    <div class="absolute -top-20 left-1/2 -z-10 h-72 w-72 -translate-x-1/2 rounded-full bg-red-500/10 blur-3xl"></div>
    <div class="absolute -bottom-20 left-1/2 -z-10 h-72 w-72 -translate-x-1/2 rounded-full bg-[#0050A1]/20 blur-3xl"></div>

    <!-- Animated Lock Visual -->
    <div class="flex justify-center">
      <div class="relative cursor-pointer transition-transform duration-200 active:scale-95"
           :class="{ 'shake-anim': shake }"
           @click="shake = true; clickCount++; setTimeout(() => shake = false, 400)">
        
        <!-- Guard Shield Glow -->
        <span class="absolute inline-flex h-full w-full animate-pulse rounded-full bg-red-500/5 opacity-50"></span>

        <!-- Padlock/Shield SVG -->
        <svg class="relative h-40 w-40 text-red-500 filter drop-shadow-[0_0_15px_rgba(239,68,68,0.4)]" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <defs>
            <linearGradient id="lockGrad" x1="0%" y1="0%" x2="100%" y2="100%">
              <stop offset="0%" stop-color="#EF4444" />
              <stop offset="50%" stop-color="#B91C1C" />
              <stop offset="100%" stop-color="#0A192F" />
            </linearGradient>
          </defs>
          <rect x="3" y="11" width="18" height="11" rx="2" fill="url(#lockGrad)" stroke="#EF4444" stroke-width="1" />
          <path d="M7 11V7a5 5 0 0 1 10 0v4" stroke="#EF4444" stroke-width="2" stroke-linecap="round" />
          <circle cx="12" cy="16" r="2" fill="#0A192F" />
        </svg>

        <!-- Speech Bubble/Warning -->
        <div class="absolute -top-12 left-1/2 -translate-x-1/2 rounded-md bg-red-950 border border-red-500/40 px-3 py-1.5 text-xs font-semibold text-red-400 shadow-lg whitespace-nowrap"
             x-show="clickCount > 0"
             x-transition>
          <span x-text="clickCount < 3 ? 'Akses Ditolak!' : 'Tetap tidak bisa masuk! 🔐'"></span>
        </div>
      </div>
    </div>

    <!-- Error Text -->
    <h1 class="mt-8 text-8xl font-black tracking-widest text-red-500 filter drop-shadow-[0_2px_8px_rgba(239,68,68,0.3)]">403</h1>
    <h2 class="mt-4 text-2xl font-bold tracking-tight text-white sm:text-3xl">Akses Tidak Diizinkan</h2>
    <p class="mt-4 text-base leading-7 text-slate-400">
      Maaf, Anda tidak memiliki izin untuk mengakses halaman ini. Periksa kembali autentikasi atau role akun Anda.
    </p>

    <!-- Navigation Buttons -->
    <div class="mt-10 flex flex-col justify-center gap-4 sm:flex-row">
      <a href="/" class="rounded-md bg-gradient-to-r from-[#00E5FF] via-[#00A2E8] to-[#0050A1] px-6 py-3 text-sm font-bold text-white shadow-lg shadow-[#0050A1]/30 hover:opacity-90 transition">
        Kembali ke Beranda
      </a>
      <a href="/login" class="rounded-md border border-[#00E5FF]/40 px-6 py-3 text-sm font-bold text-[#00E5FF] hover:bg-[#00E5FF]/10 transition">
        Login Ulang
      </a>
    </div>
  </div>
</body>
</html>
