<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PelangganController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

function sidagasWhatsappUrl(string $message = ''): string
{
    $defaultMessage = 'Halo Admin PD Syahrizal Galon, saya ingin bertanya tentang layanan SIDAGAS.';

    return 'https://wa.me/6285224365449?text=' . rawurlencode($message ?: $defaultMessage);
}

function sidagasProducts(): array
{
    return [
        [
            'name' => 'Galon Isi Ulang',
            'slug' => 'galon-isi-ulang',
            'category' => 'Galon',
            'description' => 'Air minum isi ulang untuk kebutuhan rumah tangga, warung, dan UMKM sekitar Indramayu.',
            'price' => 'Rp 5.000',
            'unit' => 'per galon',
            'status' => 'Tersedia',
            'image' => 'img/products/sampul-galon.webp',
        ],
        [
            'name' => 'Gas LPG 3 Kg',
            'slug' => 'gas-lpg-3-kg',
            'category' => 'LPG',
            'description' => 'LPG 3 kg bersubsidi untuk kebutuhan memasak rumah tangga dan usaha kecil.',
            'price' => 'Rp. 22.000',
            'unit' => 'per tabung',
            'status' => 'Tersedia',
            'image' => 'img/products/sampul-gas.webp',
        ],
        [
            'name' => 'Aqua Galon',
            'slug' => 'aqua-galon',
            'category' => 'Air Kemasan',
            'description' => 'Air kemasan bermerek untuk pelanggan yang membutuhkan pilihan air minum premium.',
            'price' => 'Rp. 20.000',
            'unit' => 'per galon',
            'status' => 'Tersedia',
            'image' => 'img/products/sampul-kemasan.webp',
        ],
        [
            'name' => 'Pulsa, Token Listrik, PDAM',
            'slug' => 'layanan-digital',
            'category' => 'Layanan Digital',
            'description' => 'Layanan digital pendamping untuk pulsa, paket data, token listrik, dan pembayaran tagihan.',
            'price' => 'Sesuai nominal',
            'unit' => 'per transaksi',
            'status' => 'Tersedia',
            'image' => 'img/products/sampul-digital.webp',
        ],
    ];
}

function sidagasBlogPosts(): array
{
    return [
        [
            'title' => 'Tips Memilih Air Isi Ulang yang Aman untuk Keluarga',
            'slug' => 'tips-memilih-air-isi-ulang-yang-aman',
            'category' => 'Tips Air Minum',
            'excerpt' => 'Kenali hal penting sebelum membeli air isi ulang agar kebutuhan minum keluarga tetap aman dan nyaman.',
            'content' => [
                'Pilih penyedia air isi ulang yang menjaga kebersihan tempat, peralatan, dan wadah galon. Pelanggan juga sebaiknya memastikan galon tertutup rapat setelah pengisian.',
                'PD Syahrizal Galon berkomitmen melayani kebutuhan air minum masyarakat sekitar Indramayu dengan pelayanan yang ramah, cepat, dan mudah dihubungi.',
            ],
        ],
        [
            'title' => 'Cara Mengetahui Tabung LPG 3 Kg yang Aman Digunakan',
            'slug' => 'cara-mengetahui-tabung-lpg-3-kg-yang-aman',
            'category' => 'Informasi LPG',
            'excerpt' => 'Periksa segel, kondisi tabung, regulator, dan aroma gas sebelum menggunakan LPG 3 kg di rumah.',
            'content' => [
                'Sebelum memakai LPG 3 kg, pastikan tabung tidak penyok parah, tidak berkarat berlebihan, dan segel masih baik saat diterima.',
                'Jika tercium bau gas, matikan sumber api, buka ventilasi, dan hubungi penjual atau pihak berwenang untuk mendapatkan bantuan.',
            ],
        ],
        [
            'title' => 'Layanan Galon Isi Ulang Delivery di Indramayu',
            'slug' => 'layanan-galon-isi-ulang-delivery-indramayu',
            'category' => 'Layanan Syahrizal Galon',
            'excerpt' => 'Pelanggan dapat memesan galon melalui WhatsApp agar admin dapat menyiapkan dan mengatur pengiriman.',
            'content' => [
                'Layanan delivery membantu pelanggan rumah tangga, warung, dan UMKM mendapatkan galon tanpa harus datang langsung ke toko.',
                'Untuk tahap saat ini, pemesanan dari website diarahkan ke WhatsApp agar admin dapat memproses pesanan secara manual dengan cepat.',
            ],
        ],
        [
            'title' => 'Kenapa Penting Menyimpan Bukti Transaksi Pembelian Galon dan LPG',
            'slug' => 'pentingnya-bukti-transaksi-galon-dan-lpg',
            'category' => 'Edukasi Pelanggan',
            'excerpt' => 'Bukti transaksi membantu pelanggan dan admin mengecek ulang pesanan, pembayaran, dan riwayat pembelian.',
            'content' => [
                'Bukti transaksi membuat proses pengecekan lebih jelas ketika ada perbedaan pembayaran, jumlah pesanan, atau status pengiriman.',
                'SIDAGAS dikembangkan untuk membantu usaha mencatat pesanan, stok, dan transaksi dengan lebih tertib secara bertahap.',
            ],
        ],
        [
            'title' => 'Cara Pesan Galon dan LPG Lewat WhatsApp di Syahrizal Galon',
            'slug' => 'cara-pesan-galon-dan-lpg-lewat-whatsapp',
            'category' => 'Berita Usaha',
            'excerpt' => 'Cukup pilih produk di website, klik Pesan via WhatsApp, lalu lengkapi jumlah, nama, alamat, dan catatan.',
            'content' => [
                'Website SIDAGAS menyediakan tombol WhatsApp otomatis agar pelanggan tidak perlu mengetik format pesanan dari awal.',
                'Admin akan menerima pesan, mengecek stok, lalu mengonfirmasi pesanan dan pengiriman secara manual sementara sistem transaksi dikembangkan.',
            ],
        ],
    ];
}

Route::get('/', function () {
    $products = sidagasProducts();
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'LocalBusiness',
        'name' => 'PD Syahrizal Galon',
        'description' => 'Galon isi ulang, LPG 3 kg, air kemasan, dan layanan digital di Indramayu.',
        'telephone' => '+6285224365449',
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => 'Jln Raya Nasional 1 Kandanghaur - Indramayu, Desa Ilir, Kandanghaur',
            'addressLocality' => 'Indramayu',
            'addressRegion' => 'Jawa Barat',
            'addressCountry' => 'ID',
        ],
    ];

    return view('landing.home', [
        'title' => 'PD Syahrizal Galon - Galon Isi Ulang dan LPG 3 Kg Indramayu',
        'description' => 'PD Syahrizal Galon menyediakan galon isi ulang, LPG 3 Kg, air kemasan, dan layanan digital untuk pelanggan rumah tangga, warung, dan UMKM di Indramayu.',
        'products' => $products,
        'schema' => $schema,
        'whatsappUrl' => sidagasWhatsappUrl('Halo Admin PD Syahrizal Galon, saya ingin memesan galon atau LPG.'),
    ]);
})->name('home');

Route::get('/tentang-kami', function () {
    return view('landing.tentang-kami', [
        'title' => 'Tentang Kami - PD Syahrizal Galon',
        'description' => 'Kenali PD Syahrizal Galon, usaha galon isi ulang dan LPG 3 kg terpercaya di Desa Ilir, Kandanghaur, Indramayu.',
        'whatsappUrl' => sidagasWhatsappUrl('Halo Admin PD Syahrizal Galon, saya ingin mengetahui layanan PD Syahrizal Galon.'),
    ]);
})->name('tentang-kami');

Route::get('/produk', function () {
    $products = sidagasProducts();
    $schema = collect($products)->map(fn ($product) => [
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $product['name'],
        'description' => $product['description'],
        'category' => $product['category'],
    ])->all();

    return view('landing.produk', [
        'title' => 'Produk Galon Isi Ulang dan LPG 3 Kg - PD Syahrizal Galon',
        'description' => 'Lihat produk PD Syahrizal Galon seperti galon isi ulang, LPG 3 Kg, air kemasan, dan layanan digital. Pesan mudah melalui WhatsApp.',
        'products' => $products,
        'schema' => $schema,
    ]);
})->name('produk');

Route::get('/blog', function () {
    return view('landing.blog.index', [
        'title' => 'Blog SIDAGAS - Tips Galon, LPG, dan Layanan Pelanggan',
        'description' => 'Artikel edukasi PD Syahrizal Galon tentang air isi ulang, LPG 3 kg, layanan delivery, transaksi, dan pemesanan via WhatsApp.',
        'posts' => sidagasBlogPosts(),
    ]);
})->name('blog');

Route::get('/blog/{slug}', function (string $slug) {
    $post = collect(sidagasBlogPosts())->firstWhere('slug', $slug);
    abort_if(!$post, 404);

    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => $post['title'],
        'description' => $post['excerpt'],
        'author' => [
            '@type' => 'Organization',
            'name' => 'PD Syahrizal Galon',
        ],
    ];

    return view('landing.blog.show', [
        'title' => $post['title'] . ' - SIDAGAS',
        'description' => $post['excerpt'],
        'post' => $post,
        'schema' => $schema,
    ]);
})->name('blog.show');

Route::get('/kontak', function () {
    return view('landing.kontak', [
        'title' => 'Kontak PD Syahrizal Galon - Pesan Galon dan LPG via WhatsApp',
        'description' => 'Hubungi PD Syahrizal Galon untuk pemesanan galon isi ulang, LPG 3 Kg, dan layanan kebutuhan rumah tangga di Indramayu.',
        'whatsappUrl' => sidagasWhatsappUrl('Halo Admin PD Syahrizal Galon, saya ingin menanyakan produk.'),
    ]);
})->name('kontak');

Route::get('/sitemap.xml', function () {
    $urls = [
        route('home'),
        route('tentang-kami'),
        route('produk'),
        route('blog'),
        route('kontak'),
    ];

    foreach (sidagasBlogPosts() as $post) {
        $urls[] = route('blog.show', $post['slug']);
    }

    return response()
        ->view('sitemap', ['urls' => $urls])
        ->header('Content-Type', 'application/xml');
})->name('sitemap');

Route::redirect('/TentangKami', '/tentang-kami');
Route::redirect('/Produk', '/produk');
Route::redirect('/Kontak', '/kontak');
Route::redirect('/Blog', '/blog');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware([RoleMiddleware::class . ':admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard']);
    Route::get('/orders', [AdminController::class, 'orders']);
    Route::get('/inventory', [AdminController::class, 'inventory']);
});

Route::middleware([RoleMiddleware::class . ':karyawan,admin'])->prefix('karyawan')->group(function () {
    Route::get('/', [KaryawanController::class, 'intake']);
    Route::get('/production', [KaryawanController::class, 'production']);
    Route::get('/ready', [KaryawanController::class, 'ready']);
});

Route::middleware([RoleMiddleware::class . ':driver'])->prefix('driver')->group(function () {
    Route::get('/', [DriverController::class, 'deliveries']);
    Route::get('/active', [DriverController::class, 'active']);
    Route::get('/history', [DriverController::class, 'history']);
});

Route::middleware([RoleMiddleware::class . ':pelanggan'])->prefix('pelanggan')->group(function () {
    Route::get('/', [PelangganController::class, 'catalog']);
    Route::get('/checkout', [PelangganController::class, 'checkout']);
    Route::get('/tracking', [PelangganController::class, 'tracking']);
});

Route::get('/dashboard', [CustomerController::class, 'index']);
