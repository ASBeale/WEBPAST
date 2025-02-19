<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['nama_role' => 'Admin', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama_role' => 'Anggota', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama_role' => 'Pengurus', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama_role' => 'PiMi', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        DB::table('role')->insert($roles);
    }
}