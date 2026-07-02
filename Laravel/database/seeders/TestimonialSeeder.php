<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testimonials = [
            ['customer_name' => 'Ibu Sari', 'customer_type' => 'Pelanggan rumah tangga', 'message' => 'Pesan galon jadi lebih mudah. Tinggal WhatsApp, admin cepat merespons dan pengiriman sampai rumah.', 'rating' => 5, 'sort_order' => 1],
            ['customer_name' => 'Pak Dedi', 'customer_type' => 'Pemilik warung', 'message' => 'Stok LPG dan galon selalu saya cek lewat admin. Pelayanannya ramah dan jelas.', 'rating' => 5, 'sort_order' => 2],
            ['customer_name' => 'Ibu Rina', 'customer_type' => 'Pelanggan setia', 'message' => 'Sudah lama langganan karena harganya jujur, pengiriman cepat, dan komunikasinya enak.', 'rating' => 5, 'sort_order' => 3],
            ['customer_name' => 'Kang Asep', 'customer_type' => 'Pelaku UMKM', 'message' => 'Untuk kebutuhan jualan, galon dan LPG dari Syahrizal Galon sangat membantu operasional harian.', 'rating' => 5, 'sort_order' => 4],
            ['customer_name' => 'Bu Neng', 'customer_type' => 'Pelanggan sekitar', 'message' => 'Kalau butuh galon mendadak, tinggal hubungi admin. Responsnya cepat dan sopan.', 'rating' => 5, 'sort_order' => 5],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::updateOrCreate(['customer_name' => $testimonial['customer_name']], $testimonial + ['is_active' => true]);
        }
    }
}
