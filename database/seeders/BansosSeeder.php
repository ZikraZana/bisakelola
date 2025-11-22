<?php

namespace Database\Seeders;

use App\Models\Bansos;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BansosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Bansos::insert([
            [
                'nama_bansos' => 'PKH (Program Keluarga Harapan)',
                'deskripsi' => 'Bantuan bersyarat bagi keluarga kurang mampu yang terdaftar dalam DTKS.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_bansos' => 'BPNT (Bantuan Pangan Non Tunai)',
                'deskripsi' => 'Bantuan sembako yang disalurkan melalui kartu elektronik (Kartu Sembako).',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_bansos' => 'BST (Bantuan Sosial Tunai)',
                'deskripsi' => 'Bantuan uang tunai dari Kemensos untuk warga terdampak krisis/pandemi.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_bansos' => 'BLT Dana Desa',
                'deskripsi' => 'Bantuan Langsung Tunai yang bersumber dari Dana Desa.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_bansos' => 'PBI JK (Penerima Bantuan Iuran Jaminan Kesehatan)',
                'deskripsi' => 'Bantuan iuran BPJS Kesehatan bagi warga kurang mampu.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_bansos' => 'PIP / KIP (Program Indonesia Pintar)',
                'deskripsi' => 'Bantuan pendidikan bagi anak usia sekolah dari keluarga miskin.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
