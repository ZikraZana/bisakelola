<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class adminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::insert([
            [
                'username' => 'ketuart',
                'password' => bcrypt('ketuart1234'),
                'role' => 'Ketua RT',
            ],
            [
                'username' => 'ketuablok',
                'password' => bcrypt('ketuablok1234'),
                'role' => 'Ketua Blok',
            ],
            [
                'username' => 'ketuabagian',
                'password' => bcrypt('ketuabagian1234'),
                'role' => 'Ketua Bagian',
            ],
        ]);
    }
}
