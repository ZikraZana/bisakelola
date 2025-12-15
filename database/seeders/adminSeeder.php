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
        $now = now();
        Admin::insert([
            [
                'username' => 'ketuart',
                'password' => bcrypt('ketuart1234'),
                'nama_lengkap' => 'MonoZikk',
                'no_handphone' => '082372818489',
                'role' => 'Ketua RT',
                'id_blok' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'username' => 'ketuablokduren',
                'password' => bcrypt('ketuablokduren1234'),
                'nama_lengkap' => 'Ketua Blok Duren',
                'no_handphone' => '082372818489',
                'role' => 'Ketua Blok',
                'id_blok' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'username' => 'ketuablokmakakau',
                'password' => bcrypt('ketuablokmakakau1234'),
                'nama_lengkap' => 'Ketua Blok Makakau',
                'no_handphone' => '082372818489',
                'role' => 'Ketua Blok',
                'id_blok' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'username' => 'ketuablokmatahari',
                'password' => bcrypt('ketuablokmatahari1234'),
                'nama_lengkap' => 'Ketua Blok Matahari',
                'no_handphone' => '082372818489',
                'role' => 'Ketua Blok',
                'id_blok' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'username' => 'ketuablokgardu',
                'password' => bcrypt('ketuablokgardu1234'),
                'nama_lengkap' => 'Ketua Blok Gardu',
                'no_handphone' => '082372818489',
                'role' => 'Ketua Blok',
                'id_blok' => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
