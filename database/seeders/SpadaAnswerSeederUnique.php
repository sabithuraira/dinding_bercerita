<?php

namespace Database\Seeders;

use App\Models\SpadaAnswer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SpadaAnswerSeederUnique extends Seeder
{
    /**
     * Run the database seeds.
     * 100 answers: question_id=1, status_approve=1, unique text 20-200 chars.
     */
    public function run(): void
    {
        $minLen = 20;
        $maxLen = 200;

        $words = [
            'Bekerja', 'lebih', 'baik', 'dengan', 'tim', 'yang', 'solid', 'dan', 'komunikasi', 'terbuka',
            'Kinerja', 'pegawai', 'meningkat', 'jika', 'lingkungan', 'nyaman', 'serta', 'apresiasi', 'diberikan',
            'Kesehatan', 'mental', 'penting', 'untuk', 'produktivitas', 'sehari-hari', 'di', 'kantor',
            'Fasilitas', 'olahraga', 'atau', 'ruang', 'istirahat', 'bisa', 'membantu', 'stres', 'berkurang',
            'Pelatihan', 'keterampilan', 'secara', 'berkala', 'membuat', 'pegawai', 'semakin', 'kompeten',
            'Work', 'life', 'balance', 'harus', 'menjadi', 'prioritas', 'agar', 'bahagia', 'tetap',
            'Feedback', 'dari', 'atasan', 'secara', 'konstruktif', 'sangat', 'berarti', 'bagi', 'perkembangan',
            'Transparansi', 'kebijakan', 'organisasi', 'membangun', 'kepercayaan', 'antara', 'pimpinan', 'bawahan',
            'Liburan', 'cukup', 'dan', 'cuti', 'yang', 'diambil', 'mencegah', 'burnout', 'pada', 'pegawai',
            'Sarapan', 'sehat', 'sebelum', 'berangkat', 'kantor', 'memberi', 'energi', 'untuk', 'aktivitas',
        ];

        for ($i = 0; $i < 100; $i++) {
            $sentenceCount = rand(2, 6);
            $parts = [];
            for ($s = 0; $s < $sentenceCount; $s++) {
                $wordCount = rand(4, 14);
                $segment = [];
                for ($w = 0; $w < $wordCount; $w++) {
                    $segment[] = $words[array_rand($words)];
                }
                $parts[] = ucfirst(implode(' ', $segment)) . (rand(0, 1) ? '.' : ',');
            }
            $base = trim(implode(' ', $parts), ' ,.');
            $base = Str::limit($base, $maxLen - 8, '');
            $uniqueSuffix = ' [' . ($i + 1) . ']';
            $text = trim(Str::limit($base . $uniqueSuffix, $maxLen, ''));
            while (Str::length($text) < $minLen) {
                $text .= ' ' . $words[array_rand($words)];
                $text = Str::limit($text, $maxLen, '');
            }

            SpadaAnswer::create([
                'question_id' => 1,
                'answer' => $text,
                'status_approve' => 1,
            ]);
        }
    }
}
