<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Kesalahan Server Internal (500) - SIDAGAS</title>
  <link rel="preconnect" href="https://rsms.me">
  <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center bg-[#0A192F] text-white antialiased font-sans">
  <div class="relative mx-auto max-w-xl px-6 py-12 text-center" x-data="{ showDetails: false }">
    <!-- Decorative Ambient Glow -->
    <div class="absolute -top-20 left-1/2 -z-10 h-72 w-72 -translate-x-1/2 rounded-full bg-[#00E5FF]/10 blur-3xl"></div>
    <div class="absolute -bottom-20 left-1/2 -z-10 h-72 w-72 -translate-x-1/2 rounded-full bg-[#0050A1]/20 blur-3xl"></div>

    <!-- Animated Pipeline System Visual -->
    <div class="flex justify-center">
      <div class="relative">
        <!-- Outer spin ring representing malfunctioned gear/valve -->
        <span class="absolute -top-4 -left-4 inline-flex h-48 w-48 animate-spin rounded-full border-4 border-dashed border-[#00E5FF]/20 [animation-duration:15s]"></span>
        
        <!-- Malfunctioning Pipe/Cylinder SVG -->
        <svg class="relative h-40 w-40 text-[#00E5FF] filter drop-shadow-[0_0_15px_rgba(0,229,255,0.3)]" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <defs>
            <linearGradient id="pipeGrad" x1="0%" y1="0%" x2="100%" y2="100%">
              <stop offset="0%" stop-color="#00E5FF" />
              <stop offset="100%" stop-color="#0050A1" />
            </linearGradient>
          </defs>
          <!-- Pipe body -->
          <path d="M4 8h16v8H4z" fill="url(#pipeGrad)" opacity="0.8" />
          <path d="M4 6h2v12H4V6zm14 0h2v12h-2V6z" fill="#00E5FF" />
          <!-- Animated pressure gauge/arrow needle -->
          <circle cx="12" cy="12" r="4" fill="#0A192F" stroke="#00E5FF" stroke-width="1.5" />
          <path d="M12 12l2-3" stroke="#EF4444" stroke-width="2" stroke-linecap="round" class="animate-pulse" />
        </svg>

        <!-- Bubble animations -->
        <span class="absolute top-10 left-8 h-3 w-3 rounded-full bg-[#00E5FF]/40 animate-bounce"></span>
        <span class="absolute top-16 right-10 h-2 w-2 rounded-full bg-[#00E5FF]/60 animate-ping"></span>
      </div>
    </div>

    <!-- Error Text -->
    <h1 class="mt-8 text-8xl font-black tracking-widest text-[#00E5FF] filter drop-shadow-[0_2px_8px_rgba(0,229,255,0.3)]">500</h1>
    <h2 class="mt-4 text-2xl font-bold tracking-tight text-white sm:text-3xl">Kesalahan Server Internal</h2>
    <p class="mt-4 text-base leading-7 text-slate-400">
      Maaf, terjadi kendala pada sistem kami. Tim teknis kami sedang menangani masalah ini untuk segera memulihkannya.
    </p>

    <!-- Navigation / Action Buttons -->
    <div class="mt-10 flex flex-col justify-center gap-4 sm:flex-row">
      <a href="/" class="rounded-md bg-gradient-to-r from-[#00E5FF] via-[#00A2E8] to-[#0050A1] px-6 py-3 text-sm font-bold text-white shadow-lg shadow-[#0050A1]/30 hover:opacity-90 transition">
        Kembali ke Beranda
      </a>
      <button @click="showDetails = !showDetails" class="rounded-md border border-[#00E5FF]/40 px-6 py-3 text-sm font-bold text-[#00E5FF] hover:bg-[#00E5FF]/10 transition">
        <span x-text="showDetails ? 'Sembunyikan Detail' : 'Tampilkan Detail'"></span>
      </button>
    </div>

    <!-- Collapsible Diagnostics Console -->
    <div class="mt-6 overflow-hidden rounded-lg border border-slate-700 bg-slate-950 p-4 text-left font-mono text-xs text-slate-400 shadow-inner"
         x-show="showDetails"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 max-h-0"
         x-transition:enter-end="opacity-100 max-h-40"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 max-h-40"
         x-transition:leave-end="opacity-0 max-h-0">
      <div class="flex items-center gap-1.5 border-b border-slate-800 pb-2 text-[#00E5FF]">
        <span class="h-2 w-2 rounded-full bg-red-500"></span>
        <span class="h-2 w-2 rounded-full bg-yellow-500"></span>
        <span class="h-2 w-2 rounded-full bg-green-500"></span>
        <span class="ml-1 text-[10px] font-semibold uppercase">System Diagnostics Console v1.0</span>
      </div>
      <div class="mt-2 space-y-1">
        <p><span class="text-red-400">[ERROR]</span> HTTP 500: Internal Server Error</p>
        <p><span class="text-slate-500">[TRACE]</span> ID: SIDAGAS-{{ date('Ymd-His') }}</p>
        <p><span class="text-[#00E5FF]">[INFO]</span> Sistem pendukung otomatis sedang aktif.</p>
        <p class="text-slate-500 animate-pulse">&gt; Harap hubungi admin jika masalah terus berlanjut...</p>
      </div>
    </div>
  </div>
</body>
</html>
