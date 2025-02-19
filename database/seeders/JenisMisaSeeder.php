<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisMisaSeeder extends Seeder
{
    public function run(): void
    {
        $jenisMisa = [
            ['jenis_misa' => 'Misa Biasa', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['jenis_misa' => 'Misa Besar', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['jenis_misa' => 'Misa Arwah', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['jenis_misa' => 'Misa Pernikahan', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        DB::table('jenis_misa')->insert($jenisMisa);
    }
}