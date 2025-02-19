<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JabatanSeeder extends Seeder
{
    public function run(): void
    {
        $jabatan = [
            ['nama_jabatan' => 'Ketua Pengurus', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama_jabatan' => 'Ketua PiMi', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama_jabatan' => 'Wakil Ketua Pengurus 1', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama_jabatan' => 'Wakil Ketua Pengurus 2', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama_jabatan' => 'Wakil Ketua PiMi 1', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama_jabatan' => 'Wakil Ketua PiMi 2', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama_jabatan' => 'Sekretaris', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama_jabatan' => 'Bendahara', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        DB::table('jabatan')->insert($jabatan);
    }
}