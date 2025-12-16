<?php

namespace Database\Seeders;

use App\Models\Desil;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DesilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Desil::insert([
            [
                'tingkat_desil' => 1,
            ],
            [
                'tingkat_desil' => 2,
            ],
            [
                'tingkat_desil' => 3,
            ],
            [
                'tingkat_desil' => 4,
            ],
            [
                'tingkat_desil' => 5,
            ],
            [
                'tingkat_desil' => null,
            ],
        ]);
    }
}
