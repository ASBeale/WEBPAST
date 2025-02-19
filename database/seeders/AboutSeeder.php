<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AboutSeeder extends Seeder
{
    public function run(): void
    {
        $abouts = [
            [
                'title' => 'Putra Altar Santo Tarsisius',
                'body' => 'Putra Altar merupakan misdinar, Misdinar merupakan pelayan Misa Kudus. Misdinar laki-laki disebut putera altar, sedangkan misdinar puteri disebut puteri altar. Misdinar merupakan remaja Katolik yang berjiwa penuh pengabdian, tanpa pamrih, menyediakan dirinya dengan rela untuk melayani Gereja dalam ibadat atau kebaktian liturgis, khususnya dalam Perayaan Ekaristi. Putra Altar Santo Tarsisius merupakan misdinar atau putera altar pada Gereja Santa Maria Imakulata. Terdapat cara agar dapat menjadi Putra Altar Santo Tarsisius dengan cara diantaranya mendaftar pendaftaran Putra Altar baru saat dibukanya lowongan Putra Altar Santo Tarsisius, mengikuti rangkaian kegiatan dan acara seperti mengikuti acara pertemuan anak dan orang tua Putra Altar baru dan mengikuti latihan teori dan praktik serta mengikuti ujian teori dan praktik yang diselenggarakan oleh pengurus Putra Altar Santo Tarsisius. Setelah menjadi Putra Altar Santo Tarsisius, anggota dapat mengikuti kegiatan-kegiatan lainnya seperti Latihan untuk misa natal dan paskah, pesta nama Santo Tarsisius, hingga kegiatan pendalaman iman.',
                'image' => 'about-images/QuuHC14FM4xldqEL9WCtpAQlOPQBo3exJ7j5gXOA.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        DB::table('about')->insert($abouts);
    }
}