<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            ['question' => 'Bagaimana cara pesan?', 'answer' => 'Pilih produk di website lalu klik tombol Pesan via WhatsApp. Lengkapi jumlah, nama, alamat, dan catatan.', 'page' => 'home', 'sort_order' => 1],
            ['question' => 'Apakah stok LPG selalu tersedia?', 'answer' => 'Stok LPG 3 kg mengikuti alokasi dan jadwal distribusi. Silakan cek stok melalui WhatsApp admin.', 'page' => 'home', 'sort_order' => 2],
            ['question' => 'Area layanan di mana saja?', 'answer' => 'Fokus layanan berada di Desa Ilir, Kandanghaur, Indramayu dan area sekitar sesuai konfirmasi admin.', 'page' => 'home', 'sort_order' => 3],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(['question' => $faq['question'], 'page' => $faq['page']], $faq + ['is_active' => true]);
        }
    }
}
