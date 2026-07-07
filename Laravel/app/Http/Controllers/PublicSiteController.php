<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\ContactMessage;
use App\Models\Faq;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use App\Models\WhatsappClick;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Throwable;

class PublicSiteController extends Controller
{
    public function home(): View
    {
        $products = Product::query()
            ->with(['category', 'image'])
            ->where('is_available', true)
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->take(4)
            ->get();

        if ($products->isEmpty()) {
            $products = Product::query()
                ->with(['category', 'image'])
                ->where('is_available', true)
                ->orderBy('sort_order')
                ->take(4)
                ->get();
        }

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => SiteSetting::value('business_name', 'PD Syahrizal Galon'),
            'description' => 'Galon isi ulang, LPG 3 kg, air kemasan, dan layanan digital di Indramayu.',
            'telephone' => '+' . SiteSetting::whatsappNumber(),
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => SiteSetting::value('address', 'Jln Raya Nasional 1 Kandanghaur - Indramayu, Desa Ilir, Kandanghaur'),
                'addressLocality' => 'Indramayu',
                'addressRegion' => 'Jawa Barat',
                'addressCountry' => 'ID',
            ],
        ];

        return view('landing.home', [
            'title' => SiteSetting::value('home_meta_title', 'PD Syahrizal Galon - Galon Isi Ulang dan LPG 3 Kg Indramayu'),
            'description' => SiteSetting::value('home_meta_description', 'PD Syahrizal Galon menyediakan galon isi ulang, LPG 3 Kg, air kemasan, dan layanan digital untuk pelanggan rumah tangga, warung, dan UMKM di Indramayu.'),
            'products' => $products,
            'productCategories' => ProductCategory::query()->where('is_active', true)->orderBy('sort_order')->get(),
            'testimonials' => Testimonial::query()->where('is_active', true)->orderBy('sort_order')->get(),
            'faqs' => Faq::query()->where('is_active', true)->whereIn('page', ['home', 'global'])->orderBy('sort_order')->get(),
            'schema' => $schema,
            'whatsappUrl' => SiteSetting::whatsappUrl('Halo Admin PD Syahrizal Galon, saya ingin memesan galon atau LPG.'),
        ]);
    }

    public function about(): View
    {
        return view('landing.tentang-kami', [
            'title' => 'Tentang Kami - PD Syahrizal Galon',
            'description' => 'Kenali PD Syahrizal Galon, usaha galon isi ulang dan LPG 3 kg terpercaya di Desa Ilir, Kandanghaur, Indramayu.',
            'whatsappUrl' => SiteSetting::whatsappUrl('Halo Admin PD Syahrizal Galon, saya ingin mengetahui layanan PD Syahrizal Galon.'),
        ]);
    }

    public function products(): View
    {
        $products = Product::query()
            ->with(['category', 'image'])
            ->where('is_available', true)
            ->orderBy('sort_order')
            ->get();

        $schema = $products->map(fn (Product $product) => [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'image' => asset($product->imagePath()),
            'description' => $product->short_description ?: $product->description,
            'category' => $product->category?->name,
            'brand' => [
                '@type' => 'Brand',
                'name' => 'PD Syahrizal Galon',
            ],
            'offers' => [
                '@type' => 'Offer',
                'url' => route('produk'),
                'priceCurrency' => 'IDR',
                'price' => (float) ($product->price ?? 0),
                'priceValidUntil' => date('Y-m-d', strtotime('+1 year')),
                'availability' => $product->is_available ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'itemCondition' => 'https://schema.org/NewCondition',
            ],
        ])->all();

        return view('landing.produk', [
            'title' => 'Produk Galon Isi Ulang dan LPG 3 Kg - PD Syahrizal Galon',
            'description' => 'Lihat produk PD Syahrizal Galon seperti galon isi ulang, LPG 3 Kg, air kemasan, dan layanan digital. Pesan mudah melalui WhatsApp.',
            'products' => $products,
            'categories' => ProductCategory::query()->where('is_active', true)->orderBy('sort_order')->get(),
            'schema' => $schema,
        ]);
    }

    public function blog(): View
    {
        return view('landing.blog.index', [
            'title' => 'Blog SIDAGAS - Tips Galon, LPG, dan Layanan Pelanggan',
            'description' => 'Artikel edukasi PD Syahrizal Galon tentang air isi ulang, LPG 3 kg, layanan delivery, transaksi, dan pemesanan via WhatsApp.',
            'categories' => BlogCategory::query()->where('is_active', true)->orderBy('name')->get(),
            'posts' => BlogPost::query()->with('category')->published()->latest('published_at')->get(),
        ]);
    }

    public function blogShow(string $slug): View
    {
        $post = BlogPost::query()
            ->with('category')
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $post->title,
            'description' => $post->excerpt,
            'author' => [
                '@type' => 'Organization',
                'name' => SiteSetting::value('business_name', 'PD Syahrizal Galon'),
            ],
        ];

        return view('landing.blog.show', [
            'title' => ($post->meta_title ?: $post->title) . ' - SIDAGAS',
            'description' => $post->meta_description ?: $post->excerpt,
            'post' => $post,
            'schema' => $schema,
        ]);
    }

    public function contact(): View
    {
        return view('landing.kontak', [
            'title' => 'Kontak PD Syahrizal Galon - Pesan Galon dan LPG via WhatsApp',
            'description' => 'Hubungi PD Syahrizal Galon untuk pemesanan galon isi ulang, LPG 3 Kg, dan layanan kebutuhan rumah tangga di Indramayu.',
            'whatsappUrl' => SiteSetting::whatsappUrl('Halo Admin PD Syahrizal Galon, saya ingin menanyakan produk.'),
        ]);
    }

    public function storeContact(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:150'],
            'subject' => ['nullable', 'string', 'max:200'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        ContactMessage::create($validated);

        return back()->with('status', 'Pesan berhasil disimpan. Admin akan menindaklanjuti secepatnya.');
    }

    public function whatsapp(Request $request): RedirectResponse
    {
        $product = Product::query()->whereKey($request->integer('product'))->first();

        WhatsappClick::create([
            'product_id' => $product?->id,
            'source_page' => $request->string('source_page')->limit(80)->toString() ?: null,
            'clicked_at' => now(),
        ]);

        $message = $request->string('message')->toString() ?: ($product?->whatsappMessage() ?? '');

        return redirect()->away(SiteSetting::whatsappUrl($message));
    }

    public function sitemap()
    {
        $blogLastMod = null;
        $productLastMod = null;

        $urls = [
            [
                'loc' => route('home'),
                'lastmod' => null,
                'priority' => '1.0',
            ],
            [
                'loc' => route('tentang-kami'),
                'lastmod' => null,
                'priority' => '0.8',
            ],
            [
                'loc' => route('produk'),
                'lastmod' => $productLastMod,
                'priority' => '0.9',
            ],
            [
                'loc' => route('blog'),
                'lastmod' => $blogLastMod,
                'priority' => '0.7',
            ],
            [
                'loc' => route('kontak'),
                'lastmod' => null,
                'priority' => '0.8',
            ],
        ];

        try {
            if (Schema::hasTable('products')) {
                $latestProduct = Product::query()->latest('updated_at')->first();
                $productLastMod = $this->sitemapLastModified($latestProduct?->updated_at);
                $urls[2]['lastmod'] = $productLastMod;
            }

            if (Schema::hasTable('blog_posts')) {
                $latestBlogPost = BlogPost::query()->published()->latest('updated_at')->first();
                $blogLastMod = $this->sitemapLastModified($latestBlogPost?->updated_at);
                $urls[3]['lastmod'] = $blogLastMod;

                BlogPost::query()->published()->get(['slug', 'updated_at'])->each(function (BlogPost $post) use (&$urls) {
                    $urls[] = [
                        'loc' => route('blog.show', $post->slug),
                        'lastmod' => $this->sitemapLastModified($post->updated_at),
                        'priority' => '0.8',
                    ];
                });
            }

            $urls[0]['lastmod'] = collect([$blogLastMod, $productLastMod])->filter()->sortDesc()->first();
        } catch (Throwable) {
            // Keep the static sitemap available even if dynamic content is temporarily unavailable.
        }

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }

    private function sitemapLastModified(mixed $value): ?string
    {
        if (!$value) {
            return null;
        }

        return $value instanceof Carbon
            ? $value->toAtomString()
            : Carbon::parse($value)->toAtomString();
    }
}
