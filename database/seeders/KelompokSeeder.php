<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelompokSeeder extends Seeder
{
    public function run(): void
    {
        $kelompok = [
            ['nama_kelompok' => 'ST. Laurensius', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama_kelompok' => 'ST. Mikael' , 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        DB::table('kelompok')->insert($kelompok);
    }
}