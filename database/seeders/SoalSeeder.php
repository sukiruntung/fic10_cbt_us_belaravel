<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SoalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Soal::factory(100)->create();
        //menambahkan data model manually

        //
    }
}
