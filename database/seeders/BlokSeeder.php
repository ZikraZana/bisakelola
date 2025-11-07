<?php

namespace Database\Seeders;

use App\Models\Blok;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BlokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Blok::insert(
            [
                [
                    'nama_blok' => 'Lrg. Duren',
                ],
                [
                    'nama_blok' => 'Makakau',
                ],
                [
                    'nama_blok' => 'Matahari',
                ],
                [
                    'nama_blok' => 'Lrg. Gardu'
                ],
            ]
        );
    }
}
