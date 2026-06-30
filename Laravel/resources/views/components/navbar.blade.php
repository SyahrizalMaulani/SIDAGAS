<header class="sticky top-0 z-30 border-b border-[#00E5FF]/40 bg-white shadow-sm" x-data="{ open: false }">
  <nav aria-label="Navigasi utama" class="mx-auto flex max-w-7xl items-center justify-between px-2 py-2 sm:px-6 lg:px-8">
    <a href="{{ route('home') }}" class="flex items-center gap-3" aria-label="SIDAGAS beranda">
      <img src="{{ asset('img/logo/Logo-Lanscape.webp') }}" alt="Logo SIDAGAS" width="320" height="136" decoding="async" class="h-15 w-40 object-contain">
    </a>

    <div class="hidden items-center gap-8 lg:flex">
      <a href="{{ route('home') }}" class="rounded-md px-3 py-2 text-sm font-semibold text-[#0A192F] hover:bg-[#00E5FF]/10">Home</a>
      <a href="{{ route('tentang-kami') }}" class="rounded-md px-3 py-2 text-sm font-semibold text-[#0A192F] hover:bg-[#00E5FF]/10">Tentang Kami</a>
      <a href="{{ route('produk') }}" class="rounded-md px-3 py-2 text-sm font-semibold text-[#0A192F] hover:bg-[#00E5FF]/10">Produk</a>
      <a href="{{ route('blog') }}" class="rounded-md px-3 py-2 text-sm font-semibold text-[#0A192F] hover:bg-[#00E5FF]/10">Blog</a>
      <a href="{{ route('kontak') }}" class="rounded-md px-3 py-2 text-sm font-semibold text-[#0A192F] hover:bg-[#00E5FF]/10">Kontak</a>
    </div>

    <div class="hidden items-center gap-3 lg:flex">
      <a href="https://wa.me/6285224365449?text={{ rawurlencode('Halo Admin PD Syahrizal Galon, saya ingin memesan dari website SIDAGAS.') }}" class="rounded-md bg-[#0B6B3A] px-4 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-[#07542D]">Pesan via WhatsApp</a>
      <a href="{{ route('login') }}" class="rounded-md border border-[#0050A1] px-4 py-2.5 text-sm font-bold text-[#0050A1] hover:bg-[#00E5FF]/10">Masuk</a>
    </div>

    <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-md border border-[#0050A1]/30 text-[#0A192F] lg:hidden" @click="open = !open" aria-label="Buka menu">
      <span class="text-2xl leading-none" x-text="open ? 'x' : '='"></span>
    </button>
  </nav>

  <div class="border-t border-[#00E5FF]/30 bg-white px-4 py-4 lg:hidden" x-show="open" x-transition>
    <div class="flex flex-col gap-2">
      <a href="{{ route('home') }}" class="rounded-md px-3 py-2 text-sm font-semibold text-[#0A192F] hover:bg-[#00E5FF]/10">Home</a>
      <a href="{{ route('tentang-kami') }}" class="rounded-md px-3 py-2 text-sm font-semibold text-[#0A192F] hover:bg-[#00E5FF]/10">Tentang Kami</a>
      <a href="{{ route('produk') }}" class="rounded-md px-3 py-2 text-sm font-semibold text-[#0A192F] hover:bg-[#00E5FF]/10">Produk</a>
      <a href="{{ route('blog') }}" class="rounded-md px-3 py-2 text-sm font-semibold text-[#0A192F] hover:bg-[#00E5FF]/10">Blog</a>
      <a href="{{ route('kontak') }}" class="rounded-md px-3 py-2 text-sm font-semibold text-[#0A192F] hover:bg-[#00E5FF]/10">Kontak</a>
      <a href="https://wa.me/6285224365449?text={{ rawurlencode('Halo Admin PD Syahrizal Galon, saya ingin memesan dari website SIDAGAS.') }}" class="mt-2 rounded-md bg-[#0B6B3A] px-3 py-2 text-center text-sm font-bold text-white">Pesan via WhatsApp</a>
      <a href="{{ route('login') }}" class="rounded-md border border-[#0050A1] px-3 py-2 text-center text-sm font-bold text-[#0050A1]">Masuk</a>
    </div>
  </div>
</header>
