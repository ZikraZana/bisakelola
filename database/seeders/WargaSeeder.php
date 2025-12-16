<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DataKeluarga;
use App\Models\AnggotaKeluarga;
use App\Models\Blok;
use App\Models\Desil;
use App\Models\Admin;
use Faker\Factory as Faker;

class WargaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID'); // Menggunakan data orang Indonesia

        // 1. Ambil Data Pendukung (Foreign Keys)
        $bloks = Blok::all();
        $desils = Desil::all();
        
        // Ambil ID admin pertama untuk mengisi kolom 'id_admin' (siapa yang menginput)
        $admin = Admin::first();
        $idAdmin = $admin ? $admin->id_admin : 1; 

        // Cek agar tidak error jika data blok/desil kosong
        if ($bloks->isEmpty() || $desils->isEmpty()) {
            $this->command->error('Error: Data Blok atau Desil kosong. Harap jalankan BlokSeeder & DesilSeeder terlebih dahulu.');
            return;
        }

        // ==========================================
        // LOGIKA UTAMA: Loop Desil -> Keluarga -> Anggota
        // ==========================================
        
        // Kita loop setiap jenis Desil agar datanya merata (Desil 1 ada, Desil 2 ada, dst)
        foreach ($desils as $desil) {
            
            // Buat 3 Keluarga (KK) untuk setiap tipe Desil
            for ($i = 0; $i < 3; $i++) {
                
                // --- LANGKAH 1: Buat Data Keluarga (Parent) ---
                $keluarga = DataKeluarga::create([
                    'no_kk'      => $faker->unique()->numerify('1671##########'),
                    'id_admin'   => $idAdmin,
                    'status'     => 1, // 1 = Aktif
                    'id_blok'    => $bloks->random()->id_blok, // Pilih blok acak
                    'id_desil'   => $desil->id_desil,          // Sesuai loop saat ini
                    'foto_ktp'   => 'placeholder_ktp.jpg',     
                    'foto_kk'    => 'placeholder_kk.jpg',      
                ]);

                // Sekarang kita punya '$keluarga->id_keluarga' yang bisa dipakai di bawah

                // --- LANGKAH 2: Buat Kepala Keluarga (Wajib Ada) ---
                $kkName = $faker->name('male'); // Simpan nama bapak buat nama belakang anak
                AnggotaKeluarga::create([
                    'id_keluarga'           => $keluarga->id_keluarga, // <--- MENYAMBUNGKAN DISINI
                    'nik_anggota'           => $faker->unique()->numerify('1671##########'),
                    'nama_lengkap'          => $kkName,
                    'tempat_lahir'          => $faker->city,
                    'tanggal_lahir'         => $faker->dateTimeBetween('-60 years', '-30 years')->format('Y-m-d'),
                    'jenis_kelamin'         => 'Laki-laki',
                    'agama'                 => 'Islam',
                    'status_perkawinan'     => 'Kawin',
                    'status_dalam_keluarga' => 'Kepala Keluarga',
                    'pendidikan'            => $faker->randomElement(['SMA', 'S1', 'SMP']),
                    'pekerjaan'             => $faker->jobTitle,
                ]);

                // --- LANGKAH 3: Buat Istri (Opsional, peluang 80%) ---
                if (rand(0, 100) < 80) {
                    AnggotaKeluarga::create([
                        'id_keluarga'           => $keluarga->id_keluarga, // <--- Sambungkan ke KK yang sama
                        'nik_anggota'           => $faker->unique()->numerify('1671##########'),
                        'nama_lengkap'          => $faker->name('female'),
                        'tempat_lahir'          => $faker->city,
                        'tanggal_lahir'         => $faker->dateTimeBetween('-55 years', '-25 years')->format('Y-m-d'),
                        'jenis_kelamin'         => 'Perempuan',
                        'agama'                 => 'Islam',
                        'status_perkawinan'     => 'Kawin',
                        'status_dalam_keluarga' => 'Istri',
                        'pendidikan'            => $faker->randomElement(['SMA', 'S1', 'SMP']),
                        'pekerjaan'             => 'Ibu Rumah Tangga',
                    ]);
                }

                // --- LANGKAH 4: Buat Anak (Acak 0 sampai 2 anak) ---
                $jumlahAnak = rand(0, 2);
                for ($j = 0; $j < $jumlahAnak; $j++) {
                    AnggotaKeluarga::create([
                        'id_keluarga'           => $keluarga->id_keluarga, // <--- Sambungkan ke KK yang sama
                        'nik_anggota'           => $faker->unique()->numerify('1671##########'),
                        'nama_lengkap'          => $faker->firstName . ' ' . explode(' ', $kkName)[1], // Nama belakang ikut bapak
                        'tempat_lahir'          => $faker->city,
                        'tanggal_lahir'         => $faker->dateTimeBetween('-17 years', '-1 years')->format('Y-m-d'),
                        'jenis_kelamin'         => $faker->randomElement(['Laki-laki', 'Perempuan']),
                        'agama'                 => 'Islam',
                        'status_perkawinan'     => 'Belum Kawin',
                        'status_dalam_keluarga' => 'Anak',
                        'pendidikan'            => $faker->randomElement(['Belum Sekolah', 'SD', 'SMP', 'SMA']),
                        'pekerjaan'             => 'Pelajar',
                    ]);
                }
            }
        }
    }
}