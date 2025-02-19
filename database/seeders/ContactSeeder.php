<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        $contacts = [
            [
                'title' => 'Instagram PAST',
                'isi' => 'https://www.instagram.com/past_smi/',
                'image' => 'contact-images/MCk90w3qgEt8XEGVO1ms9YaH1rly2MXqELaoBpcG.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        DB::table('contact')->insert($contacts);
    }
}