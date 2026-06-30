<x-layout-web :title="$title" :description="$description">
  <section class="bg-white py-16">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
      <div class="max-w-3xl">
        <p class="text-sm font-bold uppercase tracking-wide text-[#0050A1]">Blog SIDAGAS</p>
        <h1 class="mt-4 text-4xl font-bold tracking-tight text-[#0A192F] sm:text-5xl">Artikel galon, LPG, dan layanan pelanggan</h1>
        <p class="mt-6 text-lg leading-8 text-slate-600">Informasi praktis untuk pelanggan PD Syahrizal Galon tentang air minum, LPG 3 kg, delivery, transaksi, dan pemesanan via WhatsApp.</p>
      </div>
    </div>
  </section>

  <section class="bg-[#F7FDFF] py-12">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
      <div class="flex flex-wrap gap-3">
        @foreach(['Tips Air Minum', 'Informasi LPG', 'Layanan Syahrizal Galon', 'Edukasi Pelanggan', 'Berita Usaha'] as $category)
          <span class="rounded-full bg-white px-4 py-2 text-sm font-bold text-[#0050A1] shadow-sm">{{ $category }}</span>
        @endforeach
      </div>

      <div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        @foreach($posts as $post)
          <article class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
            <p class="text-xs font-bold uppercase tracking-wide text-[#0050A1]">{{ $post['category'] }}</p>
            <h2 class="mt-3 text-xl font-bold text-[#0A192F]">{{ $post['title'] }}</h2>
            <p class="mt-3 text-sm leading-6 text-slate-600">{{ $post['excerpt'] }}</p>
            <a href="{{ route('blog.show', $post['slug']) }}" class="mt-6 inline-flex text-sm font-bold text-[#0050A1] hover:text-[#0A192F]">Baca artikel</a>
          </article>
        @endforeach
      </div>
    </div>
  </section>
</x-layout-web>
