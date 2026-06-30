<footer class="bg-gradient-to-br from-[#0050A1] via-[#00A2E8] to-[#00E5FF] text-white" aria-labelledby="footer-heading">
  <h2 id="footer-heading" class="sr-only">Footer</h2>
  <div class="mx-auto grid max-w-7xl gap-10 px-6 py-12 sm:grid-cols-2 lg:grid-cols-4 lg:px-8">
    <div>
      <p class="text-2xl font-bold text-white">SIDAGAS</p>
      <p class="mt-3 text-sm leading-6 text-white/85">Sistem Informasi Dagang Syahrizal Galon untuk galon isi ulang, LPG 3 kg, air kemasan, dan layanan digital di Indramayu.</p>
    </div>
    <div>
      <p class="font-semibold">Menu</p>
      <div class="mt-4 flex flex-col gap-2 text-sm text-white/85">
        <a href="{{ route('tentang-kami') }}" class="hover:text-white">Tentang Kami</a>
        <a href="{{ route('produk') }}" class="hover:text-white">Produk</a>
        <a href="{{ route('blog') }}" class="hover:text-white">Blog</a>
        <a href="{{ route('kontak') }}" class="hover:text-white">Kontak</a>
      </div>
    </div>
    <div>
      <p class="font-semibold">Layanan</p>
      <div class="mt-4 flex flex-col gap-2 text-sm text-white/85">
        <span>Galon isi ulang</span>
        <span>Gas LPG 3 kg</span>
        <span>Aqua dan air kemasan</span>
        <span>Pulsa, token listrik, PDAM</span>
      </div>
    </div>
    <div>
      <p class="font-semibold">Kontak</p>
      <div class="mt-4 space-y-2 text-sm text-white/85">
        <p>Desa Ilir, Kandanghaur, Indramayu</p>
        <p>WhatsApp: 0852-2436-5449</p>
        <a href="https://wa.me/6285224365449" class="inline-flex rounded-md bg-[#0B6B3A] px-4 py-2 font-bold text-white hover:bg-[#07542D]">Hubungi Admin</a>
      </div>
    </div>
  </div>
  <div class="border-t border-white/20 px-6 py-6 text-center text-xs text-white/80">
    &copy; {{ date('Y') }} SIDAGAS - PD Syahrizal Galon. All rights reserved.
  </div>
</footer>
