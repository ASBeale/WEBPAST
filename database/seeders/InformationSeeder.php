<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class informationSeeder extends Seeder
{
    public function run(): void
    {
        $informations = [
            [
                'title' => 'Pendaftaran Putra Altar Baru 2025',
                'slug' => 'pendaftaran-putra-altar-baru-2025',
                'body' => 'halooo teman teman‼️
                            kalian ada yang mau melayani Tuhan dengan bergabung ke keluarga PAST ga nih??

                            PAST lagi buka pendaftarann loh sampai tanggal 9 Juni 2024 aja nih‼️🤩🥳
                            langsung ajaa yuk yang mau join PAST gass daftar lewat link dibawah ini yaa 🥳‼️

                            📍 https://bit.ly/PendaftaranPASTBaru2024 📍',
                'image' => 'information-images/hu5UUO7icI81ygVzGxPjf7m111gyQxOCvbQMiLou.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            [
                'title' => 'Pesta Nama Putra Altar Santo Tarsisius 2025',
                'slug' => 'pesta-nama-putra-altar-santo-tarsisius-2025',
                'body' => 'Hello PAST Family! 👋 Ada kabar gembira nih 😊! Pendaftaran untuk Acara Pesta Nama PAST 2024 telah resmi dibuka 🎉 dan GRATIS 🤩! Yuk, ramaikan acara seru ini pada Minggu, 1 September 2024, pukul 09.00 - 15.30 WIB, bertempat di AULA KASIH. Jangan lupa pakai Baju PAST ya!

                Apa saja kegiatan seru yang akan kita nikmati bersama? Akan ada Misa Pagi Bersama ⛪, Doorprize 🎉, Games 🎲, Lunch & PotLuck 🍿, serta momen indah Memory Kebersamaan PAST 2024 📸. Jangan lupa untuk BBM (Bawa Botol Minum) ya, guys 🥤!

                Buruan daftar sekarang karena kapasitas terbatas! Klik link pendaftaran di sini: 🔗 https://smi.my.id/PestaNamaPAST24. Kalian juga bisa ikut berpartisipasi dalam sesi PotLuck dengan membawa snack atau makanan favorit untuk dinikmati bersama. Daftar partisipasi PotLuck di sini: 🔗 https://bit.ly/PartisipasiPotLuck.',
                'image' => 'information-images/H674iHLuV87Y7jgDxIOnNAkI0hE9boSiBi8359qn.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        DB::table('informations')->insert($informations);
    }
}