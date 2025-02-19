<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            KelompokSeeder::class,
            JabatanSeeder::class,
            JenisMisaSeeder::class,
            JenisKegiatanSeeder::class,
            AnggotaSeeder::class,
            AboutSeeder::class,
            ContactSeeder::class,
            informationSeeder::class
        ]);
    }
}
