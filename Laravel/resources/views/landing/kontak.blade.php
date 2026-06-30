<x-layout-web :title="$title" :description="$description">
  <section class="bg-white py-16">
    <div class="mx-auto grid max-w-7xl gap-12 px-6 lg:grid-cols-2 lg:px-8">
      <div>
        <p class="text-sm font-bold uppercase tracking-wide text-[#0050A1]">Kontak</p>
        <h1 class="mt-4 text-4xl font-bold tracking-tight text-[#0A192F] sm:text-5xl">Hubungi PD Syahrizal Galon</h1>
        <p class="mt-6 text-lg leading-8 text-slate-600">Pesan galon isi ulang, LPG 3 kg, air kemasan, atau layanan digital melalui WhatsApp admin.</p>
        <a href="{{ $whatsappUrl }}" class="mt-8 inline-flex rounded-md bg-[#0B6B3A] px-6 py-3 text-sm font-bold text-white hover:bg-[#07542D]">WhatsApp Admin</a>
      </div>
      <div class="rounded-lg border border-[#B8F7FF] bg-[#F7FDFF] p-8">
        <h2 class="text-2xl font-bold text-[#0A192F]">Informasi Kontak</h2>
        <dl class="mt-6 space-y-5 text-sm">
          <div>
            <dt class="font-bold text-[#0A192F]">WhatsApp</dt>
            <dd class="mt-1 text-slate-600">0852-2436-5449</dd>
          </div>
          <div>
            <dt class="font-bold text-[#0A192F]">Alamat</dt>
            <dd class="mt-1 text-slate-600">Desa Ilir, Kandanghaur, Indramayu, Jawa Barat</dd>
          </div>
          <div>
            <dt class="font-bold text-[#0A192F]">Jam Operasional</dt>
            <dd class="mt-1 text-slate-600">Setiap hari, konfirmasi waktu layanan melalui WhatsApp admin.</dd>
          </div>
          <div>
            <dt class="font-bold text-[#0A192F]">Area Layanan</dt>
            <dd class="mt-1 text-slate-600">Kandanghaur, Indramayu, dan area sekitar sesuai konfirmasi admin.</dd>
          </div>
        </dl>
      </div>
    </div>
  </section>

  <section class="bg-[#F7FDFF] py-16">
    <div class="mx-auto grid max-w-7xl gap-8 px-6 lg:grid-cols-2 lg:px-8">
      <div class="rounded-lg bg-white p-6 shadow-sm">
        <h2 class="text-2xl font-bold text-[#0A192F]">Lokasi Usaha</h2>
        <p class="mt-3 text-sm leading-6 text-slate-600">Kunjungi titik lokasi usaha PD Syahrizal Galon melalui Google Maps.</p>
        <div class="mt-6 overflow-hidden rounded-lg border border-[#B8F7FF] bg-[#F2FCFF]">
          <iframe
            src="https://www.google.com/maps?q=PD%20Syahrizal%20Galon%20Desa%20Ilir%20Kandanghaur%20Indramayu&output=embed"
            class="aspect-video w-full"
            style="border:0;"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            title="Lokasi PD Syahrizal Galon di Google Maps"></iframe>
        </div>
        <a href="https://maps.app.goo.gl/m2kbdWmKGGNDr78z7" target="_blank" rel="noopener" class="mt-5 inline-flex rounded-md bg-[#0050A1] px-5 py-3 text-sm font-bold text-white hover:bg-[#0A192F]">Buka di Google Maps</a>
      </div>

      <div class="rounded-lg bg-white p-6 shadow-sm" x-data="{ nama: '', alamat: '', pesan: '' }">
        <h2 class="text-2xl font-bold text-[#0A192F]">Form Pesan, Kritik, atau Saran</h2>
        <p class="mt-3 text-sm leading-6 text-slate-600">Isi form singkat ini, lalu kirim langsung ke WhatsApp admin.</p>
        <form class="mt-6 space-y-4" @submit.prevent="
          window.open('https://wa.me/6285224365449?text=' + encodeURIComponent('Halo Admin PD Syahrizal Galon, saya ingin mengirim pesan pengalaman pembelian produk.\n\nNama: ' + nama + '\nAlamat: ' + alamat + '\nPesan: ' + pesan), '_blank')
        ">
          <div>
            <label for="nama" class="text-sm font-bold text-[#0A192F]">Nama</label>
            <input id="nama" x-model="nama" type="text" class="mt-2 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#0050A1] focus:outline-none focus:ring-2 focus:ring-[#B8F7FF]" placeholder="Nama pelanggan">
          </div>
          <div>
            <label for="alamat" class="text-sm font-bold text-[#0A192F]">Alamat</label>
            <input id="alamat" x-model="alamat" type="text" class="mt-2 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#0050A1] focus:outline-none focus:ring-2 focus:ring-[#B8F7FF]" placeholder="Alamat pengiriman atau domisili">
          </div>
          <div>
            <label for="pesan" class="text-sm font-bold text-[#0A192F]">Pesan</label>
            <textarea id="pesan" x-model="pesan" rows="5" class="mt-2 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#0050A1] focus:outline-none focus:ring-2 focus:ring-[#B8F7FF]" placeholder="Tulis pesan, kritik, atau saran"></textarea>
          </div>
          <button type="submit" class="w-full rounded-md bg-[#0B6B3A] px-5 py-3 text-sm font-bold text-white hover:bg-[#07542D]">Kirim via WhatsApp</button>
        </form>
      </div>
    </div>
  </section>
</x-layout-web>
