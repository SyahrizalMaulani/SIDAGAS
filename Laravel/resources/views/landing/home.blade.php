<x-layout-web :title="$title" :description="$description" :schema="$schema">
  <section class="bg-white">
    <div class="mx-auto grid max-w-7xl gap-12 px-6 py-16 lg:grid-cols-2 lg:items-center lg:px-8 lg:py-20">
      <div>
        <p class="text-sm font-bold uppercase tracking-wide text-[#0050A1]">PD Syahrizal Galon</p>
        <h1 class="mt-4 text-4xl font-bold tracking-tight text-[#0A192F] sm:text-6xl">Galon Isi Ulang & LPG 3 Kg Terpercaya di Indramayu</h1>
        <p class="mt-6 text-lg leading-8 text-slate-600">Melayani kebutuhan rumah tangga, warung, UMKM, restoran, dan toko kelontong melalui galon isi ulang, LPG 3 kg, air kemasan, serta layanan digital.</p>
        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
          <a href="{{ $whatsappUrl }}" class="rounded-md bg-[#0B6B3A] px-6 py-3 text-center text-sm font-bold text-white shadow-sm hover:bg-[#07542D]">Pesan Sekarang</a>
          <a href="{{ route('produk') }}" class="rounded-md border border-[#0050A1] px-6 py-3 text-center text-sm font-bold text-[#0050A1] hover:bg-[#F2FCFF]">Lihat Produk</a>
        </div>
      </div>
      <div class="relative">
        <img src="{{ asset('img/Sampul-Utama.webp') }}" alt="Toko PD Syahrizal Galon di Indramayu" width="610" height="456" decoding="async" fetchpriority="high" class="aspect-[4/3] w-full rounded-lg object-cover shadow-xl">
      </div>
    </div>
  </section>

  <section class="bg-white py-16">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
      <div class="mx-auto grid max-w-5xl items-center gap-8 rounded-lg bg-gradient-to-r from-[#00E5FF] via-[#00A2E8] to-[#0050A1] p-8 shadow-xl shadow-[#0050A1]/20 sm:p-10 lg:grid-cols-[0.8fr_1.2fr]">
        <div class="text-center lg:text-left" x-data="{ count: 0, target: 100, timer: null, ready: false, init() { const observer = new IntersectionObserver(([entry]) => { if (!this.ready) { this.ready = true; if (entry.isIntersecting) return; } if (entry.isIntersecting) { this.animate(); } else { this.reset(); } }, { threshold: 0.45 }); observer.observe(this.$el); }, animate() { this.reset(); const step = Math.max(1, Math.ceil(this.target / 45)); this.timer = setInterval(() => { this.count = Math.min(this.target, this.count + step); if (this.count >= this.target) clearInterval(this.timer); }, 28); }, reset() { if (this.timer) clearInterval(this.timer); this.timer = null; this.count = 0; } }">
          <p class="text-sm font-bold uppercase tracking-wide text-white/80">Kepercayaan Pelanggan</p>
          <p class="mt-4 text-6xl font-bold tracking-tight text-white sm:text-7xl"><span x-text="count">100</span>++</p>
          <p class="mt-2 text-2xl font-bold text-white">Pelanggan Setia</p>
        </div>
        <div class="text-center lg:text-left">
          <h2 class="text-3xl font-bold text-white">Dipercaya pelanggan lokal Indramayu</h2>
          <p class="mt-4 leading-7 text-white/85">Rumah tangga, warung, UMKM, dan pelanggan sekitar memilih PD Syahrizal Galon karena layanan yang dekat, mudah dihubungi, serta memahami kebutuhan galon, LPG, air kemasan, dan layanan digital harian.</p>
          <a href="https://wa.me/6285224365449?text={{ rawurlencode('Halo Admin PD Syahrizal Galon, saya ingin jadi pelanggan setia.') }}" class="mt-7 inline-flex items-center rounded-md bg-white px-5 py-3 text-sm font-bold text-[#0050A1] hover:bg-[#F2FCFF]">Jadi pelanggan setia -&gt;</a>
        </div>
      </div>
    </div>
  </section>

  <section class="bg-[#F2FCFF] py-16">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
      <div class="max-w-2xl">
        <p class="text-sm font-bold uppercase tracking-wide text-[#0050A1]">Layanan Utama</p>
        <h2 class="mt-3 text-3xl font-bold text-[#0A192F]">Kebutuhan harian pelanggan dalam satu tempat</h2>
      </div>
      <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
        @foreach(['Galon isi ulang', 'Gas LPG 3 kg', 'Aqua dan air kemasan', 'Pulsa, token, PDAM'] as $service)
          <div class="rounded-lg border border-[#B8F7FF] bg-white p-6 shadow-sm">
            <div class="mb-4 h-2 w-12 rounded-full bg-gradient-to-r from-[#00E5FF] via-[#00A2E8] to-[#0050A1]"></div>
            <h3 class="text-lg font-bold text-[#0A192F]">{{ $service }}</h3>
            <p class="mt-3 text-sm leading-6 text-slate-600">Dipesan mudah melalui WhatsApp dan diproses admin PD Syahrizal Galon.</p>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <section class="bg-white py-16">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
      <div class="flex flex-col justify-between gap-6 sm:flex-row sm:items-end">
        <div>
          <p class="text-sm font-bold uppercase tracking-wide text-[#0050A1]">Produk Unggulan</p>
          <h2 class="mt-3 text-3xl font-bold text-[#0A192F]">Produk yang paling sering dipesan</h2>
        </div>
        <a href="{{ route('produk') }}" class="text-sm font-bold text-[#0050A1] hover:text-[#0A192F]">Lihat semua produk</a>
      </div>
      <div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-4">
        @foreach($products as $product)
          @php
            $isFeaturedProduct = in_array($product['slug'], ['galon-isi-ulang', 'gas-lpg-3-kg']);
            $message = "Halo Admin PD Syahrizal Galon, saya ingin memesan:\n\nProduk: {$product['name']}\nJumlah:\nNama:\nAlamat:\nCatatan:\n\nSaya mendapatkan informasi dari website SIDAGAS.";
          @endphp
          <article class="relative overflow-hidden rounded-lg border bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg {{ $isFeaturedProduct ? 'border-[#00E5FF] shadow-[#0050A1]/15 ring-2 ring-[#00E5FF]/40' : 'border-slate-200' }}">
            @if($isFeaturedProduct)
              <div class="absolute left-4 top-4 z-10 rounded-full bg-gradient-to-r from-[#00E5FF] via-[#00A2E8] to-[#0050A1] px-3 py-1 text-xs font-bold uppercase tracking-wide text-white shadow-sm">Produk Unggulan</div>
            @endif
            <img src="{{ asset($product['image']) }}" alt="{{ $product['name'] }} PD Syahrizal Galon" width="305" height="228" loading="lazy" decoding="async" class="h-44 w-full {{ $isFeaturedProduct ? 'bg-[#F2FCFF]' : 'bg-slate-100' }} object-contain p-4">
            <div class="p-5">
              <p class="text-xs font-bold uppercase tracking-wide text-[#0050A1]">{{ $product['category'] }}</p>
              <h3 class="mt-2 text-lg font-bold text-[#0A192F]">{{ $product['name'] }}</h3>
              <p class="mt-2 text-sm leading-6 text-slate-600">{{ $product['description'] }}</p>
              <div class="mt-4 flex items-center justify-between text-sm">
                <span class="font-bold text-[#0A192F]">{{ $product['price'] }}</span>
                <span class="rounded-full bg-emerald-50 px-3 py-1 font-semibold text-emerald-700">{{ $product['status'] }}</span>
              </div>
              <a href="https://wa.me/6285224365449?text={{ rawurlencode($message) }}" class="mt-5 block rounded-md bg-[#0B6B3A] px-4 py-2.5 text-center text-sm font-bold text-white hover:bg-[#07542D]">Pesan via WhatsApp</a>
            </div>
          </article>
        @endforeach
      </div>
    </div>
  </section>

  <section class="bg-[#F7FDFF] py-16">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
      <div class="flex flex-col justify-between gap-6 sm:flex-row sm:items-end">
        <div>
          <p class="text-sm font-bold uppercase tracking-wide text-[#0050A1]">Review Pelanggan</p>
          <h2 class="mt-3 text-3xl font-bold text-[#0A192F]">Pengalaman dari mereka yang setia</h2>
        </div>
        <a href="https://wa.me/6285224365449?text={{ rawurlencode('Halo Admin PD Syahrizal Galon, saya ingin merasakan layanan PD Syahrizal Galon.') }}" class="text-sm font-bold text-[#0050A1] hover:text-[#0A192F]">Mereka sudah merasakannya, kamu kapan?</a>
      </div>

      <div class="sidagas-review-marquee mt-10 overflow-hidden">
        <div class="sidagas-review-track flex w-max gap-5">
          @foreach([
            ['name' => 'Ibu Sari', 'role' => 'Pelanggan rumah tangga', 'review' => 'Pesan galon jadi lebih mudah. Tinggal WhatsApp, admin cepat merespons dan pengiriman sampai rumah.'],
            ['name' => 'Pak Dedi', 'role' => 'Pemilik warung', 'review' => 'Stok LPG dan galon selalu saya cek lewat admin. Pelayanannya ramah dan jelas.'],
            ['name' => 'Ibu Rina', 'role' => 'Pelanggan setia', 'review' => 'Sudah lama langganan karena harganya jujur, pengiriman cepat, dan komunikasinya enak.'],
            ['name' => 'Kang Asep', 'role' => 'Pelaku UMKM', 'review' => 'Untuk kebutuhan jualan, galon dan LPG dari Syahrizal Galon sangat membantu operasional harian.'],
            ['name' => 'Bu Neng', 'role' => 'Pelanggan sekitar', 'review' => 'Kalau butuh galon mendadak, tinggal hubungi admin. Responsnya cepat dan sopan.'],
          ] as $review)
            <article class="w-80 shrink-0 rounded-lg border border-[#B8F7FF] bg-white p-6 shadow-sm shadow-[#0050A1]/5">
              <div class="mb-4 h-2 w-12 rounded-full bg-gradient-to-r from-[#00E5FF] via-[#00A2E8] to-[#0050A1]"></div>
              <p class="text-sm leading-6 text-slate-600">"{{ $review['review'] }}"</p>
              <div class="mt-5">
                <p class="font-bold text-[#0A192F]">{{ $review['name'] }}</p>
                <p class="text-sm text-[#0050A1]">{{ $review['role'] }}</p>
              </div>
            </article>
          @endforeach
          @foreach([
            ['name' => 'Ibu Sari', 'role' => 'Pelanggan rumah tangga', 'review' => 'Pesan galon jadi lebih mudah. Tinggal WhatsApp, admin cepat merespons dan pengiriman sampai rumah.'],
            ['name' => 'Pak Dedi', 'role' => 'Pemilik warung', 'review' => 'Stok LPG dan galon selalu saya cek lewat admin. Pelayanannya ramah dan jelas.'],
            ['name' => 'Ibu Rina', 'role' => 'Pelanggan setia', 'review' => 'Sudah lama langganan karena harganya jujur, pengiriman cepat, dan komunikasinya enak.'],
            ['name' => 'Kang Asep', 'role' => 'Pelaku UMKM', 'review' => 'Untuk kebutuhan jualan, galon dan LPG dari Syahrizal Galon sangat membantu operasional harian.'],
            ['name' => 'Bu Neng', 'role' => 'Pelanggan sekitar', 'review' => 'Kalau butuh galon mendadak, tinggal hubungi admin. Responsnya cepat dan sopan.'],
          ] as $review)
            <article class="w-80 shrink-0 rounded-lg border border-[#B8F7FF] bg-white p-6 shadow-sm shadow-[#0050A1]/5" aria-hidden="true">
              <div class="mb-4 h-2 w-12 rounded-full bg-gradient-to-r from-[#00E5FF] via-[#00A2E8] to-[#0050A1]"></div>
              <p class="text-sm leading-6 text-slate-600">"{{ $review['review'] }}"</p>
              <div class="mt-5">
                <p class="font-bold text-[#0A192F]">{{ $review['name'] }}</p>
                <p class="text-sm text-[#0050A1]">{{ $review['role'] }}</p>
              </div>
            </article>
          @endforeach
        </div>
      </div>
    </div>
    <style>
      .sidagas-review-track {
        animation: sidagas-review-slide 34s linear infinite;
      }

      .sidagas-review-marquee:hover .sidagas-review-track {
        animation-play-state: paused;
      }

      @keyframes sidagas-review-slide {
        from { transform: translateX(0); }
        to { transform: translateX(calc(-50% - 10px)); }
      }
    </style>
  </section>

  <section class="bg-white py-16">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <p class="text-sm font-bold uppercase tracking-wide text-[#0050A1]">Keunggulan Layanan</p>
        <h2 class="mt-3 text-3xl font-bold text-[#0A192F]">Syahrizal Galon pilihan terbaik bagi Anda</h2>
        <p class="mt-5 leading-7 text-slate-600">Kami bekerja berdasarkan nilai inti yang memastikan kualitas, kepercayaan, dan kepuasan pelanggan selalu menjadi prioritas utama.</p>

      <dl class="mt-14 flex flex-wrap justify-center gap-x-8 gap-y-12">
        <div class="flex flex-col basis-full md:basis-[45%] lg:basis-[30%]">
          <dt class="flex items-center gap-x-3 text-lg font-semibold leading-7 text-[#0A192F]">
            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-br from-[#00E5FF] via-[#00A2E8] to-[#0050A1]">
              <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
              </svg>
            </div>
            Kualitas Terjamin
          </dt>
          <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-600">
            <p class="flex-auto">Air galon dari sumber mata air Gunung Ciremai dan gas asli bersubsidi terjamin keasliannya.</p>
          </dd>
        </div>

        <div class="flex flex-col basis-full md:basis-[45%] lg:basis-[30%]">
          <dt class="flex items-center gap-x-3 text-lg font-semibold leading-7 text-[#0A192F]">
            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-br from-[#00E5FF] via-[#00A2E8] to-[#0050A1]">
              <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path d="M10 3.5a1.5 1.5 0 013 0V4a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1.25a1 1 0 00-.88.5l-1.5 2.5a1 1 0 01-1.74 0l-1.5-2.5a1 1 0 00-.88-.5H4a1 1 0 01-1-1V6a1 1 0 011-1h3a1 1 0 001-1v-.5z" />
                <path d="M10 12.5a1.5 1.5 0 013 0V13a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1.25a1 1 0 00-.88.5l-1.5 2.5a1 1 0 01-1.74 0l-1.5-2.5a1 1 0 00-.88-.5H4a1 1 0 01-1-1v-3a1 1 0 011-1h3a1 1 0 001-1v-.5z" />
              </svg>
            </div>
            Kejujuran
          </dt>
          <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-600">
            <p class="flex-auto">Kami beroperasi dengan transparansi penuh. Takaran yang pas, harga yang jujur, dan tidak ada biaya tersembunyi. Kepercayaan Anda adalah aset utama kami.</p>
          </dd>
        </div>

        <div class="flex flex-col basis-full md:basis-[45%] lg:basis-[30%]">
          <dt class="flex items-center gap-x-3 text-lg font-semibold leading-7 text-[#0A192F]">
            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-br from-[#00E5FF] via-[#00A2E8] to-[#0050A1]">
              <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path d="M3.25 4A2.25 2.25 0 001 6.25v7.5A2.25 2.25 0 003.25 16h13.5A2.25 2.25 0 0019 13.75v-7.5A2.25 2.25 0 0016.75 4H3.25zM10 6a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 0110 6zm0 4a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 0110 10zM13 6a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 0113 6zm0 4a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 0113 10zM7 6a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 017 6zm0 4a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 017 10z" />
              </svg>
            </div>
            Harga Terjangkau
          </dt>
          <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-600">
            <p class="flex-auto">Menyediakan kebutuhan pokok berkualitas dengan harga yang kompetitif dan ramah di kantong masyarakat.</p>
          </dd>
        </div>

        <div class="flex flex-col basis-full md:basis-[45%] lg:basis-[30%]">
          <dt class="flex items-center gap-x-3 text-lg font-semibold leading-7 text-[#0A192F]">
            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-br from-[#00E5FF] via-[#00A2E8] to-[#0050A1]">
              <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path d="M10 8a3 3 0 100-6 3 3 0 000 6zM3.465 14.493a1.23 1.23 0 00.41 1.412A9.957 9.957 0 0010 18c2.31 0 4.438-.784 6.131-2.095a1.23 1.23 0 00.41-1.412A9.99 9.99 0 0010 12c-2.31 0-4.438.784-6.131 2.095z" />
              </svg>
            </div>
            Pelayanan Ramah
          </dt>
          <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-600">
            <p class="flex-auto">Setiap interaksi, dari pemesanan hingga pengantaran, kami Melayani Setulus Hati.</p>
          </dd>
        </div>

        <div class="flex flex-col basis-full md:basis-[45%] lg:basis-[30%]">
          <dt class="flex items-center gap-x-3 text-lg font-semibold leading-7 text-[#0A192F]">
            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-br from-[#00E5FF] via-[#00A2E8] to-[#0050A1]">
              <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd" />
              </svg>
            </div>
            Pengiriman Cepat & Tepat
          </dt>
          <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-600">
            <p class="flex-auto">Kami menghargai waktu Anda. Pesanan diantar sesuai jadwal yang dijanjikan, langsung ke depan pintu rumah Anda.</p>
          </dd>
        </div>
      </dl>
    </div>
  </section>

  <section class="bg-white py-16">
    <div class="mx-auto max-w-4xl px-6 lg:px-8" x-data="{ open: 1 }">
      <p class="text-center text-sm font-bold uppercase tracking-wide text-[#0050A1]">FAQ</p>
      <h2 class="mt-3 text-center text-3xl font-bold text-[#0A192F]">Pertanyaan umum pelanggan</h2>
      <div class="mt-8 divide-y divide-slate-200 rounded-lg border border-slate-200 bg-white">
        @foreach([
          'Bagaimana cara pesan?' => 'Pilih produk di website lalu klik tombol Pesan via WhatsApp. Lengkapi jumlah, nama, alamat, dan catatan.',
          'Apakah stok LPG selalu tersedia?' => 'Stok LPG 3 kg mengikuti alokasi dan jadwal distribusi. Silakan cek stok melalui WhatsApp admin.',
          'Area layanan di mana saja?' => 'Fokus layanan berada di Desa Ilir, Kandanghaur, Indramayu dan area sekitar sesuai konfirmasi admin.',
        ] as $question => $answer)
          <div class="p-5">
            <button class="flex w-full items-center justify-between text-left font-bold text-[#0A192F]" @click="open = open === {{ $loop->iteration }} ? 0 : {{ $loop->iteration }}">
              {{ $question }}
              <span>+</span>
            </button>
            <p class="mt-3 text-sm leading-6 text-slate-600" x-show="open === {{ $loop->iteration }}">{{ $answer }}</p>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <section class="bg-[#F2FCFF] py-16 text-[#0A192F]">
    <div class="mx-auto max-w-4xl px-6 text-center lg:px-8">
      <h2 class="text-3xl font-bold">Butuh galon atau LPG hari ini?</h2>
      <p class="mt-4 text-slate-600">Hubungi admin PD Syahrizal Galon untuk cek stok, harga, dan pengiriman.</p>
      <a href="{{ $whatsappUrl }}" class="mt-8 inline-flex rounded-md bg-[#0B6B3A] px-6 py-3 text-sm font-bold text-white hover:bg-[#07542D]">Hubungi Admin</a>
    </div>
  </section>
</x-layout-web>
