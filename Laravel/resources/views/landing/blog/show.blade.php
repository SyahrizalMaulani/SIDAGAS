<x-layout-web :title="$title" :description="$description" :schema="$schema">
  <article class="bg-white py-16">
    <div class="mx-auto max-w-3xl px-6 lg:px-8">
      <a href="{{ route('blog') }}" class="text-sm font-bold text-[#0050A1] hover:text-[#0A192F]">Kembali ke Blog</a>
      <p class="mt-8 text-sm font-bold uppercase tracking-wide text-[#0050A1]">{{ $post['category'] }}</p>
      <h1 class="mt-4 text-4xl font-bold tracking-tight text-[#0A192F] sm:text-5xl">{{ $post['title'] }}</h1>
      <p class="mt-6 text-lg leading-8 text-slate-600">{{ $post['excerpt'] }}</p>
      <div class="mt-10 space-y-6 text-base leading-8 text-slate-700">
        @foreach($post['content'] as $paragraph)
          <p>{{ $paragraph }}</p>
        @endforeach
      </div>
      <div class="mt-12 rounded-lg bg-[#F7FDFF] p-6">
        <h2 class="text-xl font-bold text-[#0A192F]">Butuh layanan PD Syahrizal Galon?</h2>
        <p class="mt-3 text-sm leading-6 text-slate-600">Hubungi admin untuk pesan galon, LPG 3 kg, air kemasan, atau layanan digital.</p>
        <a href="https://wa.me/6285224365449?text={{ rawurlencode('Halo Admin PD Syahrizal Galon, saya membaca artikel di website dan ingin bertanya tentang layanan.') }}" class="mt-5 inline-flex rounded-md bg-[#0B6B3A] px-5 py-3 text-sm font-bold text-white hover:bg-[#07542D]">Hubungi Admin</a>
      </div>
    </div>
  </article>
</x-layout-web>
