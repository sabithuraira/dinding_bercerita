<?php

namespace Database\Seeders;

use App\Models\KataMotivasi;
use Illuminate\Database\Seeder;

class KataMotivasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Kutipan dari buku & tokoh populer, dalam Bahasa Indonesia.
     */
    public function run(): void
    {
        $quotes = [
            [
                'kata_motivasi' => 'Ketika kamu benar-benar menginginkan sesuatu, seluruh alam semesta bersekongkol untuk membantumu meraihnya.',
                'dikutip_dari' => 'Paulo Coelho, Sang Alkemis (The Alchemist)',
            ],
            [
                'kata_motivasi' => 'Satu-satunya cara melakukan pekerjaan besar adalah mencintai apa yang kamu lakukan.',
                'dikutip_dari' => 'Steve Jobs',
            ],
            [
                'kata_motivasi' => 'Kegagalan adalah kesempatan untuk memulai lagi dengan lebih cerdas.',
                'dikutip_dari' => 'Henry Ford',
            ],
            [
                'kata_motivasi' => 'Pendidikan adalah senjata paling mematikan, karena dengan itu kamu dapat mengubah dunia.',
                'dikutip_dari' => 'Nelson Mandela',
            ],
            [
                'kata_motivasi' => 'Orang yang tidak pernah membuat kesalahan adalah orang yang tidak pernah mencoba sesuatu yang baru.',
                'dikutip_dari' => 'Albert Einstein',
            ],
            [
                'kata_motivasi' => 'Keberanian tidak selalu mengaum. Kadang keberanian adalah suara kecil di penghujung hari yang berkata, besok aku akan mencoba lagi.',
                'dikutip_dari' => 'Mary Anne Radmacher',
            ],
            [
                'kata_motivasi' => 'Apa yang kita pikirkan, itulah yang kita jadikan. Apa yang kita rasakan menarik kita. Apa yang kita bayangkan, kita wujudkan.',
                'dikutip_dari' => 'Napoleon Hill, Think and Grow Rich',
            ],
            [
                'kata_motivasi' => 'Jangan menunggu; waktu tidak akan pernah tepat. Mulailah dari mana kamu berada sekarang.',
                'dikutip_dari' => 'Napoleon Hill',
            ],
            [
                'kata_motivasi' => 'Sukses bukanlah kunci kebahagiaan. Kebahagiaan adalah kunci kesuksesan. Jika kamu mencintai apa yang kamu lakukan, kamu akan sukses.',
                'dikutip_dari' => 'Albert Schweitzer',
            ],
            [
                'kata_motivasi' => 'Hidup ini 10% apa yang terjadi padamu dan 90% bagaimana kamu meresponsnya.',
                'dikutip_dari' => 'Charles R. Swindoll',
            ],
            [
                'kata_motivasi' => 'Jangan bandingkan dirimu dengan orang lain. Bandingkan dirimu dengan dirimu yang kemarin.',
                'dikutip_dari' => 'Anonim',
            ],
            [
                'kata_motivasi' => 'Kesempatan tidak datang dua kali. Tapi kamu bisa membuat kesempatan baru setiap hari.',
                'dikutip_dari' => 'Dale Carnegie, How to Win Friends and Influence People',
            ],
            [
                'kata_motivasi' => 'Kegelapan tidak bisa mengusir kegelapan; hanya cahaya yang bisa. Kebencian tidak bisa mengusir kebencian; hanya cinta yang bisa.',
                'dikutip_dari' => 'Martin Luther King Jr.',
            ],
            [
                'kata_motivasi' => 'Mulailah dari mana kamu berada. Gunakan apa yang kamu punya. Lakukan apa yang kamu bisa.',
                'dikutip_dari' => 'Arthur Ashe',
            ],
            [
                'kata_motivasi' => 'Buku adalah teman yang paling pendiam dan paling setia; penasihat yang paling mudah diakses dan paling bijak.',
                'dikutip_dari' => 'Charles W. Eliot',
            ],
        ];

        foreach ($quotes as $item) {
            KataMotivasi::create([
                'kata_motivasi' => $item['kata_motivasi'],
                'dikutip_dari' => $item['dikutip_dari'],
                'created_nip' => null,
                'is_active' => 1,
            ]);
        }
    }
}
