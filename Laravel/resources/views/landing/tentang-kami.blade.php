<x-layout-web :title="$title" :description="$description">
  <section class="bg-white">
    <div class="mx-auto grid max-w-7xl gap-12 px-6 py-16 lg:grid-cols-2 lg:items-center lg:px-8 lg:py-20">
      <div>
        <p class="text-sm font-bold uppercase tracking-wide text-[#0050A1]">Tentang Kami</p>
        <h1 class="mt-4 text-4xl font-bold tracking-tight text-[#0A192F] sm:text-5xl">PD Syahrizal Galon melayani kebutuhan harian masyarakat Indramayu</h1>
        <p class="mt-6 text-lg leading-8 text-slate-600">Berlokasi di Desa Ilir, Kandanghaur, Indramayu, PD Syahrizal Galon hadir dengan visi Melayani Setulus Hati untuk pelanggan rumah tangga, warung, UMKM, restoran, dan toko kelontong.</p>
        <a href="{{ $whatsappUrl }}" class="mt-8 inline-flex rounded-md bg-[#0B6B3A] px-6 py-3 text-sm font-bold text-white hover:bg-[#07542D]">Hubungi Kami</a>
      </div>
      <img src="{{ asset('img/Sampul-toko.webp') }}" alt="Profil usaha PD Syahrizal Galon" width="610" height="456" decoding="async" fetchpriority="high" class="aspect-[4/3] w-full rounded-lg object-cover shadow-xl">
    </div>
  </section>

  <section class="bg-[#F7FDFF] py-16">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
      <div class="grid items-center gap-8 rounded-lg bg-gradient-to-r from-[#00E5FF] via-[#00A2E8] to-[#0050A1] p-6 shadow-lg shadow-[#0050A1]/5 lg:grid-cols-[0.9fr_1.1fr] lg:p-8">
        <img src="{{ asset('img/Direktur.webp') }}" alt="Pendiri PD Syahrizal Galon" width="610" height="456" loading="lazy" decoding="async" class="aspect-[4/3] w-full rounded-lg object-cover">
        <div>
          <p class="text-sm font-bold uppercase tracking-wide text-white/80">Sambutan Pendiri</p>
          <blockquote class="mt-5 text-xl font-semibold leading-9 text-white">"Kami percaya bahwa usaha bukan hanya tentang menjual produk, tetapi tentang menjaga kepercayaan pelanggan. Sejak awal berdiri, PD Syahrizal Galon berusaha hadir dekat dengan masyarakat, menyediakan kebutuhan air minum dan energi rumah tangga dengan pelayanan yang tulus, cepat, dan amanah."</blockquote>
          <div class="mt-6 border-l-4 border-[#F7FDFF] pl-4">
            <p class="font-bold text-white">H. Sakim, S.E, M.A.P</p>
            <p class="text-sm text-white/80">Direktur PD Syahrizal Galon</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="bg-white py-16">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
      <div class="max-w-3xl">
        <p class="text-sm font-bold uppercase tracking-wide text-[#0050A1]">Perjalanan Usaha Kami</p>
        <h2 class="mt-3 text-3xl font-bold text-[#0A192F]">Dari usaha sederhana menuju layanan yang lebih lengkap</h2>
        <p class="mt-5 leading-7 text-slate-600">Perjalanan PD Syahrizal Galon dimulai dari usaha sederhana yang berfokus pada kebutuhan air minum masyarakat sekitar. Seiring waktu, usaha ini terus berkembang dengan menambah layanan, memperluas pelanggan, dan mulai beradaptasi dengan kebutuhan digital.</p>
      </div>

      <div class="relative mt-12">
        <div class="hidden absolute left-0 right-0 top-1/2 h-1 -translate-y-1/2 rounded-full bg-gradient-to-r from-[#00E5FF] via-[#00A2E8] to-[#0050A1] lg:block"></div>
        <div class="grid gap-6 lg:grid-cols-5">
          @foreach([
            ['year' => '2009', 'icon' => 'M3 21h18M5 21V8l7-5 7 5v13M9 21v-6h6v6', 'title' => 'Awal Berdiri', 'desc' => 'PD Syahrizal Galon berdiri pada 5 Maret 2009 di Desa Ilir, Kecamatan Kandanghaur, Kabupaten Indramayu. Usaha ini berawal dari penjualan air minum isi ulang yang bersumber dari mata air pegunungan.'],
            ['year' => '2010-2020', 'icon' => 'M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m0-4a4 4 0 100-8 4 4 0 000 8zm8 0a4 4 0 100-8 4 4 0 000 8z', 'title' => 'Mulai Dipercaya Pelanggan', 'desc' => 'Dengan mengutamakan kualitas air, kebersihan galon, dan pelayanan yang dekat dengan masyarakat, usaha mulai dipercaya pelanggan rumah tangga, warung, pedagang kecil, dan pelanggan sekitar.'],
            ['year' => '2021', 'icon' => 'M8 7h8m-8 4h8m-7 8h6a3 3 0 003-3V7a5 5 0 00-10 0v9a3 3 0 003 3z', 'title' => 'Pangkalan LPG 3 Kg', 'desc' => 'Pada 1 Januari 2021, PD Syahrizal Galon resmi bekerja sama dengan Agen LPG PT Sukses Hasil Bersama dan mendirikan pangkalan LPG 3 Kg untuk kebutuhan energi masyarakat.'],
            ['year' => '2021-Sekarang', 'icon' => 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z', 'title' => 'Pengembangan Lini Layanan', 'desc' => 'Selain galon isi ulang dan LPG 3 Kg, usaha menyediakan air minum kemasan serta layanan digital seperti pulsa, paket data, token listrik, dan pembayaran PDAM.'],
            ['year' => 'Ke Depan', 'icon' => 'M4 7h16M4 12h16M4 17h10', 'title' => 'Pengembangan SIDAGAS', 'desc' => 'PD Syahrizal Galon mulai diarahkan menuju sistem informasi SIDAGAS untuk mendukung pemesanan, pencatatan transaksi, pengelolaan stok, dan pelayanan pelanggan.'],
          ] as $milestone)
            <article class="relative rounded-lg border border-[#B8F7FF] bg-white p-5 shadow-sm">
              <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-lg bg-gradient-to-br from-[#00E5FF] via-[#00A2E8] to-[#0050A1]">
                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" d="{{ $milestone['icon'] }}" />
                </svg>
              </div>
              <p class="text-sm font-bold text-[#0050A1]">{{ $milestone['year'] }}</p>
              <h3 class="mt-2 text-lg font-bold text-[#0A192F]">{{ $milestone['title'] }}</h3>
              <p class="mt-3 text-sm leading-6 text-slate-600">{{ $milestone['desc'] }}</p>
            </article>
          @endforeach
        </div>
      </div>
    </div>
  </section>

  <section class="bg-[#F7FDFF] py-16">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
      <div class="max-w-3xl">
        <p class="text-sm font-bold uppercase tracking-wide text-[#0050A1]">Legalitas Usaha</p>
        <h2 class="mt-3 text-3xl font-bold text-[#0A192F]">Operasional resmi dan bertanggung jawab</h2>
        <p class="mt-5 leading-7 text-slate-600">Sebagai usaha yang melayani kebutuhan pokok masyarakat, PD Syahrizal Galon menjalankan operasional dengan memperhatikan aspek legalitas, kepercayaan, dan tanggung jawab pelayanan.</p>
      </div>
      <div class="mt-10 grid gap-6 lg:grid-cols-2">
        <article class="rounded-lg border border-[#B8F7FF] bg-white p-7 shadow-sm">
          <h3 class="text-xl font-bold text-[#0A192F]">Legalitas PD Syahrizal Galon</h3>
          <p class="mt-4 leading-7 text-slate-600">PD Syahrizal Galon telah memiliki Surat Izin Usaha sebagai dasar legalitas dalam menjalankan aktivitas perdagangan air minum isi ulang, air kemasan, LPG, dan layanan kebutuhan rumah tangga lainnya. Legalitas ini menjadi bentuk komitmen usaha dalam menjalankan kegiatan perdagangan secara resmi dan bertanggung jawab.</p>
        </article>
        <article class="rounded-lg border border-[#B8F7FF] bg-white p-7 shadow-sm">
          <h3 class="text-xl font-bold text-[#0A192F]">Legalitas Pangkalan LPG 3 Kg</h3>
          <p class="mt-4 leading-7 text-slate-600">Sejak tahun 2021, PD Syahrizal Galon telah menjadi pangkalan LPG 3 Kg melalui kerja sama dengan Agen LPG PT Sukses Hasil Bersama. Legalitas pangkalan ini menjadi dasar dalam penyaluran LPG 3 Kg kepada pelanggan rumah tangga, warung, restoran, dan pelaku usaha kecil di sekitar wilayah operasional.</p>
        </article>
      </div>
    </div>
  </section>

  <section class="bg-gradient-to-r from-[#00E5FF] via-[#00A2E8] to-[#0050A1] py-16 text-white">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
      <p class="text-sm font-bold uppercase tracking-wide text-white/80">Lini Bisnis</p>
      <h2 class="mt-3 text-3xl font-bold">Layanan yang tersedia</h2>
      <div class="mt-10 grid gap-5 md:grid-cols-2 lg:grid-cols-4">
        @foreach(['Galon isi ulang', 'Gas LPG 3 kg', 'Aqua dan air kemasan', 'Layanan digital'] as $line)
          <div class="rounded-lg border border-white/30 bg-white/15 p-6 shadow-sm">
            <h3 class="text-lg font-bold text-white">{{ $line }}</h3>
            <p class="mt-3 text-sm leading-6 text-white/85">Dikelola untuk membantu kebutuhan pelanggan harian di area Indramayu dan sekitarnya.</p>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <section class="bg-white py-16">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
      <div class="max-w-3xl">
        <p class="text-sm font-bold uppercase tracking-wide text-[#0050A1]">Nilai yang Kami Pegang</p>
        <h2 class="mt-3 text-3xl font-bold text-[#0A192F]">Prinsip layanan yang memperkuat kepercayaan</h2>
      </div>
      <div class="sidagas-values-marquee mt-10 overflow-hidden">
        <div class="sidagas-values-track flex w-max gap-5">
          @foreach([
            ['title' => 'Amanah', 'desc' => 'Menjaga kepercayaan pelanggan dalam setiap transaksi.'],
            ['title' => 'Dekat dengan Masyarakat', 'desc' => 'Hadir sebagai penyedia kebutuhan harian di lingkungan sekitar.'],
            ['title' => 'Cepat dan Responsif', 'desc' => 'Melayani pesanan pelanggan melalui toko, WhatsApp, dan pengantaran.'],
            ['title' => 'Berkualitas', 'desc' => 'Mengutamakan kebersihan, kualitas produk, dan keamanan layanan.'],
            ['title' => 'Peduli Sosial', 'desc' => 'Berkontribusi dalam kegiatan sosial, bantuan masyarakat, dan kegiatan lingkungan.'],
          ] as $value)
            <article class="w-80 shrink-0 rounded-lg border border-[#B8F7FF] bg-[#F7FDFF] p-6 shadow-sm">
              <h3 class="text-xl font-bold text-[#0A192F]">{{ $value['title'] }}</h3>
              <p class="mt-3 text-sm leading-6 text-slate-600">{{ $value['desc'] }}</p>
            </article>
          @endforeach
          @foreach([
            ['title' => 'Amanah', 'desc' => 'Menjaga kepercayaan pelanggan dalam setiap transaksi.'],
            ['title' => 'Dekat dengan Masyarakat', 'desc' => 'Hadir sebagai penyedia kebutuhan harian di lingkungan sekitar.'],
            ['title' => 'Cepat dan Responsif', 'desc' => 'Melayani pesanan pelanggan melalui toko, WhatsApp, dan pengantaran.'],
            ['title' => 'Berkualitas', 'desc' => 'Mengutamakan kebersihan, kualitas produk, dan keamanan layanan.'],
            ['title' => 'Peduli Sosial', 'desc' => 'Berkontribusi dalam kegiatan sosial, bantuan masyarakat, dan kegiatan lingkungan.'],
          ] as $value)
            <article class="w-80 shrink-0 rounded-lg border border-[#B8F7FF] bg-[#F7FDFF] p-6 shadow-sm" aria-hidden="true">
              <h3 class="text-xl font-bold text-[#0A192F]">{{ $value['title'] }}</h3>
              <p class="mt-3 text-sm leading-6 text-slate-600">{{ $value['desc'] }}</p>
            </article>
          @endforeach
        </div>
      </div>
    </div>
  </section>

  <section class="bg-[#F2FCFF] py-16">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
      <div class="max-w-3xl">
        <p class="text-sm font-bold uppercase tracking-wide text-[#0050A1]">Galeri Usaha</p>
        <h2 class="mt-3 text-3xl font-bold text-[#0A192F]">Aktivitas dan layanan PD Syahrizal Galon</h2>
      </div>
      <div class="mt-10 grid grid-cols-2 gap-4 lg:grid-cols-4 lg:grid-rows-2">
        <img src="{{ asset('img/Sampul-toko.webp') }}" alt="Toko PD Syahrizal Galon" width="610" height="456" loading="lazy" decoding="async" class="col-span-2 row-span-2 h-full min-h-80 rounded-lg object-cover shadow-sm">
        <img src="{{ asset('img/products/sampul-galon.webp') }}" alt="Layanan galon isi ulang" width="305" height="228" loading="lazy" decoding="async" class="h-44 w-full rounded-lg object-cover shadow-sm">
        <img src="{{ asset('img/products/sampul-gas.webp') }}" alt="Layanan LPG 3 Kg" width="305" height="228" loading="lazy" decoding="async" class="h-44 w-full rounded-lg object-cover shadow-sm">
        <img src="{{ asset('img/products/sampul-kemasan.webp') }}" alt="Air minum kemasan" width="305" height="228" loading="lazy" decoding="async" class="h-44 w-full rounded-lg object-cover shadow-sm">
        <img src="{{ asset('img/products/sampul-digital.webp') }}" alt="Layanan digital pelanggan" width="305" height="228" loading="lazy" decoding="async" class="h-44 w-full rounded-lg object-cover shadow-sm">
      </div>
      <div class="mt-12 text-center">
        <p class="mx-auto max-w-3xl leading-7 text-slate-600">Untuk pengiriman galon, LPG, dan layanan lain, pelanggan dapat menghubungi admin untuk memastikan jangkauan dan ketersediaan stok di Desa Ilir, Kandanghaur, Indramayu dan area sekitar.</p>
      <a href="{{ $whatsappUrl }}" class="mt-8 inline-flex rounded-md bg-[#0B6B3A] px-6 py-3 text-sm font-bold text-white hover:bg-[#07542D]">Hubungi Admin</a>
      </div>
    </div>
  </section>
  <style>
    .sidagas-values-track {
      animation: sidagas-values-slide 32s linear infinite;
    }

    .sidagas-values-marquee:hover .sidagas-values-track {
      animation-play-state: paused;
    }

    @keyframes sidagas-values-slide {
      from { transform: translateX(0); }
      to { transform: translateX(calc(-50% - 10px)); }
    }
  </style>
</x-layout-web>
