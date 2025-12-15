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
                'nama_lengkap' => 'MonoZikk',
                'no_handphone' => '082372818489',
                'role' => 'Ketua RT',
            ],
            [
                'username' => 'ketuablok',
                'password' => bcrypt('ketuablok1234'),
                'nama_lengkap' => 'MonoBlokk',
                'no_handphone' => '082372823589',
                'role' => 'Ketua Blok',
            ],
            [
                'username' => 'ketuabagian',
                'password' => bcrypt('ketuabag1234'),
                'nama_lengkap' => 'MonoBagg',
                'no_handphone' => '082372234589',
                'role' => 'Ketua Bagian',
            ],
        ]);
    }
}
