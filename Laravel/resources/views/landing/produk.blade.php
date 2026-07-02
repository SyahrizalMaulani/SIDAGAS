<x-layout-web :title="$title" :description="$description" :schema="$schema">
  <section class="bg-white py-16">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
      <div class="max-w-3xl">
        <p class="text-sm font-bold uppercase tracking-wide text-[#0050A1]">Produk SIDAGAS</p>
        <h1 class="mt-4 text-4xl font-bold tracking-tight text-[#0A192F] sm:text-5xl">Galon isi ulang, LPG 3 kg, air kemasan, dan layanan digital</h1>
        <p class="mt-6 text-lg leading-8 text-slate-600">Pilih produk yang dibutuhkan, lalu klik Pesan via WhatsApp. Admin akan mengecek stok dan memproses pesanan secara manual.</p>
      </div>
    </div>
  </section>

  <section class="bg-[#F7FDFF] py-12" x-data="{ category: 'Semua' }">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
      <div class="flex flex-wrap gap-3">
        <button type="button" @click="category = 'Semua'" :class="category === 'Semua' ? 'bg-gradient-to-r from-[#00E5FF] via-[#00A2E8] to-[#0050A1] text-white' : 'bg-white text-slate-700 hover:bg-[#F2FCFF]'" class="rounded-full px-4 py-2 text-sm font-bold shadow-sm">Semua</button>
        @foreach($categories as $category)
          <button type="button" @click="category = @js($category->name)" :class="category === @js($category->name) ? 'bg-gradient-to-r from-[#00E5FF] via-[#00A2E8] to-[#0050A1] text-white' : 'bg-white text-slate-700 hover:bg-[#F2FCFF]'" class="rounded-full px-4 py-2 text-sm font-bold shadow-sm">{{ $category->name }}</button>
        @endforeach
      </div>

      <div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-4">
        @foreach($products as $product)
          <article x-show="category === 'Semua' || category === @js($product->category?->name)" class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
            <img src="{{ asset($product->imagePath()) }}" alt="{{ $product->name }} tersedia di PD Syahrizal Galon" width="305" height="228" loading="lazy" decoding="async" class="h-48 w-full bg-white object-contain p-5">
            <div class="p-5">
              <p class="text-xs font-bold uppercase tracking-wide text-[#0050A1]">{{ $product->category?->name }}</p>
              <h2 class="mt-2 text-xl font-bold text-[#0A192F]">{{ $product->name }}</h2>
              <p class="mt-3 text-sm leading-6 text-slate-600">{{ $product->short_description ?: $product->description }}</p>
              <dl class="mt-5 space-y-2 text-sm">
                <div class="flex justify-between gap-4">
                  <dt class="text-slate-500">Harga</dt>
                  <dd class="font-bold text-[#0A192F]">{{ $product->priceLabel() }}</dd>
                </div>
                <div class="flex justify-between gap-4">
                  <dt class="text-slate-500">Satuan</dt>
                  <dd class="font-bold text-[#0A192F]">{{ $product->unit }}</dd>
                </div>
                <div class="flex justify-between gap-4">
                  <dt class="text-slate-500">Status</dt>
                  <dd class="font-bold text-emerald-700">{{ $product->statusLabel() }}</dd>
                </div>
              </dl>
              <a href="{{ route('whatsapp.click', ['product' => $product->id, 'source_page' => 'produk', 'message' => $product->whatsappMessage()]) }}" class="mt-6 block rounded-md bg-[#0B6B3A] px-4 py-3 text-center text-sm font-bold text-white hover:bg-[#07542D]">Pesan via WhatsApp</a>
            </div>
          </article>
        @endforeach
      </div>
    </div>
  </section>

  <section class="bg-white py-16">
    <div class="mx-auto max-w-4xl px-6 text-center lg:px-8">
      <h2 class="text-3xl font-bold text-[#0A192F]">Butuh bantuan memilih produk?</h2>
      <p class="mt-4 text-slate-600">Admin dapat membantu cek stok galon, LPG, Aqua, dan layanan digital yang sedang tersedia.</p>
      <a href="{{ route('whatsapp.click', ['source_page' => 'produk', 'message' => 'Halo Admin PD Syahrizal Galon, saya ingin bertanya ketersediaan produk.']) }}" class="mt-8 inline-flex rounded-md bg-[#0B6B3A] px-6 py-3 text-sm font-bold text-white hover:bg-[#07542D]">Tanya Admin</a>
    </div>
  </section>
</x-layout-web>
