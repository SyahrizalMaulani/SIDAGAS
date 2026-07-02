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
            <dd class="mt-1 text-slate-600">{{ $siteSettings['whatsapp_display'] ?? '0852-2436-5449' }}</dd>
          </div>
          <div>
            <dt class="font-bold text-[#0A192F]">Alamat</dt>
            <dd class="mt-1 text-slate-600">{{ $siteSettings['address'] ?? 'Desa Ilir, Kandanghaur, Indramayu, Jawa Barat' }}</dd>
          </div>
          <div>
            <dt class="font-bold text-[#0A192F]">Jam Operasional</dt>
            <dd class="mt-1 text-slate-600">{{ $siteSettings['opening_hours'] ?? 'Setiap hari, konfirmasi waktu layanan melalui WhatsApp admin.' }}</dd>
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
            src="{{ $siteSettings['google_maps_embed_url'] ?? 'https://www.google.com/maps?q=PD%20Syahrizal%20Galon%20Desa%20Ilir%20Kandanghaur%20Indramayu&output=embed' }}"
            class="aspect-video w-full"
            style="border:0;"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            title="Lokasi PD Syahrizal Galon di Google Maps"></iframe>
        </div>
        <a href="{{ $siteSettings['google_maps_url'] ?? 'https://maps.app.goo.gl/m2kbdWmKGGNDr78z7' }}" target="_blank" rel="noopener" class="mt-5 inline-flex rounded-md bg-[#0050A1] px-5 py-3 text-sm font-bold text-white hover:bg-[#0A192F]">Buka di Google Maps</a>
      </div>

      <div class="rounded-lg bg-white p-6 shadow-sm">
        <h2 class="text-2xl font-bold text-[#0A192F]">Form Pesan, Kritik, atau Saran</h2>
        <p class="mt-3 text-sm leading-6 text-slate-600">Isi form singkat ini agar pesan tersimpan dan dapat ditindaklanjuti admin.</p>
        @if(session('status'))
          <div class="mt-5 rounded-md border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-700">
            {{ session('status') }}
          </div>
        @endif
        @if($errors->any())
          <div class="mt-5 rounded-md border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <ul class="list-disc space-y-1 pl-5">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif
        <form action="{{ route('kontak.store') }}" method="POST" class="mt-6 space-y-4">
          @csrf
          <div>
            <label for="name" class="text-sm font-bold text-[#0A192F]">Nama</label>
            <input id="name" name="name" value="{{ old('name') }}" required type="text" class="mt-2 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#0050A1] focus:outline-none focus:ring-2 focus:ring-[#B8F7FF]" placeholder="Nama pelanggan">
          </div>
          <div>
            <label for="phone" class="text-sm font-bold text-[#0A192F]">Nomor WhatsApp</label>
            <input id="phone" name="phone" value="{{ old('phone') }}" type="text" class="mt-2 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#0050A1] focus:outline-none focus:ring-2 focus:ring-[#B8F7FF]" placeholder="08xx">
          </div>
          <div>
            <label for="email" class="text-sm font-bold text-[#0A192F]">Email</label>
            <input id="email" name="email" value="{{ old('email') }}" type="email" class="mt-2 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#0050A1] focus:outline-none focus:ring-2 focus:ring-[#B8F7FF]" placeholder="nama@email.com">
          </div>
          <div>
            <label for="subject" class="text-sm font-bold text-[#0A192F]">Subjek</label>
            <input id="subject" name="subject" value="{{ old('subject') }}" type="text" class="mt-2 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#0050A1] focus:outline-none focus:ring-2 focus:ring-[#B8F7FF]" placeholder="Pesanan, kritik, atau saran">
          </div>
          <div>
            <label for="message" class="text-sm font-bold text-[#0A192F]">Pesan</label>
            <textarea id="message" name="message" required rows="5" class="mt-2 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#0050A1] focus:outline-none focus:ring-2 focus:ring-[#B8F7FF]" placeholder="Tulis pesan, kritik, atau saran">{{ old('message') }}</textarea>
          </div>
          <button type="submit" class="w-full rounded-md bg-[#0B6B3A] px-5 py-3 text-sm font-bold text-white hover:bg-[#07542D]">Simpan Pesan</button>
        </form>
      </div>
    </div>
  </section>
</x-layout-web>
