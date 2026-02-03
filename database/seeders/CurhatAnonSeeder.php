<?php

namespace Database\Seeders;

use App\Models\CurhatAnon;
use Illuminate\Database\Seeder;

class CurhatAnonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contents = [
            'Hari ini aku merasa sangat lelah dengan semua tekanan di kantor. Mungkin perlu liburan sebentar.',
            'Terima kasih sudah mendengarkan. Kadang kita hanya butuh tempat untuk berbagi tanpa dihakimi.',
            'Aku mencoba untuk lebih baik setiap hari. Semoga besok lebih baik dari hari ini.',
            'Tidak semua orang perlu tahu perjuangan kita. Tapi setidaknya ada tempat seperti ini.',
            'Kadang aku bertanya-tanya apakah keputusan yang aku ambil sudah benar. Hanya waktu yang bisa menjawab.',
            'Hari ini aku belajar untuk menerima hal yang tidak bisa aku ubah. Proses yang tidak mudah.',
            'Berbagi cerita di sini membuat beban terasa lebih ringan. Terima kasih sudah ada.',
            'Setiap orang punya perjalanan masing-masing. Aku memilih untuk tetap berjalan meski pelan.',
            'Kadang kita hanya butuh didengar. Tidak selalu butuh solusi, cukup empati.',
            'Besok adalah hari baru. Aku akan bangun dan mencoba lagi dengan semangat baru.',
        ];

        foreach ($contents as $content) {
            CurhatAnon::create([
                'content' => $content,
                'status_verifikasi' => 2, // Disetujui
            ]);
        }
    }
}
