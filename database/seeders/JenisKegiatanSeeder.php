<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisKegiatanSeeder extends Seeder
{
    public function run(): void
    {
        $jenisKegiatan = [
            ['jenis_kegiatan' => 'Latihan', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['jenis_kegiatan' => 'Pesta', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['jenis_kegiatan' => 'Retret', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['jenis_kegiatan' => 'Pendalaman Iman', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        DB::table('jenis_kegiatan')->insert($jenisKegiatan);
    }
}