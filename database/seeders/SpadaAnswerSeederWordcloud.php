<?php

namespace Database\Seeders;

use App\Models\SpadaAnswer;
use Illuminate\Database\Seeder;

class SpadaAnswerSeederWordcloud extends Seeder
{
    /**
     * Run the database seeds.
     * 100 answers: question_id=1, status_approve=1, short text 5-30 chars (duplicates allowed for word cloud).
     */
    public function run(): void
    {
        $pool = [
            'Kerja tim', 'Komunikasi', 'Kesehatan', 'Produktivitas', 'Apresiasi', 'Lingkungan',
            'Fasilitas', 'Pelatihan', 'Balance', 'Feedback', 'Transparansi', 'Liburan',
            'Cuti', 'Sarapan', 'Olahraga', 'Istirahat', 'Stres', 'Bahagia', 'Nyaman',
            'Kantor', 'Atasan', 'Bawahan', 'Kebijakan', 'Keterampilan', 'Energi',
            'Burnout', 'Prioritas', 'Solid', 'Terbuka', 'Konstruktif', 'Kompeten',
            'Pimpinan', 'Pegawai', 'Organisasi', 'Kinerja', 'Mental', 'Fisik',
        ];

        for ($i = 0; $i < 100; $i++) {
            $phrase = $pool[array_rand($pool)];
            $len = strlen($phrase);
            if ($len < 5) {
                $phrase = $phrase . ' baik';
            }
            if ($len > 30) {
                $phrase = substr($phrase, 0, 30);
            }

            SpadaAnswer::create([
                'question_id' => 1,
                'answer' => $phrase,
                'status_approve' => 1,
            ]);
        }
    }
}
