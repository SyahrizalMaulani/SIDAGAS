<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminId = User::query()->where('role', 'admin')->value('id');
        $categoryBySlug = BlogCategory::query()->get()->keyBy('slug');

        $posts = [
            ['category' => 'tips-air-minum', 'title' => 'Tips Memilih Air Isi Ulang yang Aman untuk Keluarga', 'slug' => 'tips-memilih-air-isi-ulang-yang-aman', 'excerpt' => 'Kenali hal penting sebelum membeli air isi ulang agar kebutuhan minum keluarga tetap aman dan nyaman.', 'content' => "Pilih penyedia air isi ulang yang menjaga kebersihan tempat, peralatan, dan wadah galon. Pelanggan juga sebaiknya memastikan galon tertutup rapat setelah pengisian.\n\nPD Syahrizal Galon berkomitmen melayani kebutuhan air minum masyarakat sekitar Indramayu dengan pelayanan yang ramah, cepat, dan mudah dihubungi."],
            ['category' => 'informasi-lpg', 'title' => 'Cara Mengetahui Tabung LPG 3 Kg yang Aman Digunakan', 'slug' => 'cara-mengetahui-tabung-lpg-3-kg-yang-aman', 'excerpt' => 'Periksa segel, kondisi tabung, regulator, dan aroma gas sebelum menggunakan LPG 3 kg di rumah.', 'content' => "Sebelum memakai LPG 3 kg, pastikan tabung tidak penyok parah, tidak berkarat berlebihan, dan segel masih baik saat diterima.\n\nJika tercium bau gas, matikan sumber api, buka ventilasi, dan hubungi penjual atau pihak berwenang untuk mendapatkan bantuan."],
            ['category' => 'layanan-syahrizal-galon', 'title' => 'Layanan Galon Isi Ulang Delivery di Indramayu', 'slug' => 'layanan-galon-isi-ulang-delivery-indramayu', 'excerpt' => 'Pelanggan dapat memesan galon melalui WhatsApp agar admin dapat menyiapkan dan mengatur pengiriman.', 'content' => "Layanan delivery membantu pelanggan rumah tangga, warung, dan UMKM mendapatkan galon tanpa harus datang langsung ke toko.\n\nUntuk tahap saat ini, pemesanan dari website diarahkan ke WhatsApp agar admin dapat memproses pesanan secara manual dengan cepat."],
            ['category' => 'edukasi-pelanggan', 'title' => 'Kenapa Penting Menyimpan Bukti Transaksi Pembelian Galon dan LPG', 'slug' => 'pentingnya-bukti-transaksi-galon-dan-lpg', 'excerpt' => 'Bukti transaksi membantu pelanggan dan admin mengecek ulang pesanan, pembayaran, dan riwayat pembelian.', 'content' => "Bukti transaksi membuat proses pengecekan lebih jelas ketika ada perbedaan pembayaran, jumlah pesanan, atau status pengiriman.\n\nSIDAGAS dikembangkan untuk membantu usaha mencatat pesanan, stok, dan transaksi dengan lebih tertib secara bertahap."],
            ['category' => 'berita-usaha', 'title' => 'Cara Pesan Galon dan LPG Lewat WhatsApp di Syahrizal Galon', 'slug' => 'cara-pesan-galon-dan-lpg-lewat-whatsapp', 'excerpt' => 'Cukup pilih produk di website, klik Pesan via WhatsApp, lalu lengkapi jumlah, nama, alamat, dan catatan.', 'content' => "Website SIDAGAS menyediakan tombol WhatsApp otomatis agar pelanggan tidak perlu mengetik format pesanan dari awal.\n\nAdmin akan menerima pesan, mengecek stok, lalu mengonfirmasi pesanan dan pengiriman secara manual sementara sistem transaksi dikembangkan."],
        ];

        foreach ($posts as $post) {
            BlogPost::updateOrCreate(['slug' => $post['slug']], [
                'blog_category_id' => $categoryBySlug[$post['category']]->id,
                'title' => $post['title'],
                'excerpt' => $post['excerpt'],
                'content' => $post['content'],
                'meta_title' => $post['title'],
                'meta_description' => $post['excerpt'],
                'status' => 'published',
                'published_at' => now(),
                'created_by' => $adminId,
            ]);
        }
    }
}
