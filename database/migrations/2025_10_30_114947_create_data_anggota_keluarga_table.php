<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data_anggota_keluarga', function (Blueprint $table) {
            $table->id('id_anggota');
            $table->foreignId('id_keluarga')->constrained('data_keluarga', 'id_keluarga')->onDelete('cascade');

            $table->string('nik_anggota');
            $table->string('nama_lengkap');
            $table->string('tempat_lahir');
            $table->string('tanggal_lahir');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->enum('agama', ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghuchu']);
            $table->enum('status_perkawinan', ['Belum Kawin', 'Kawin', 'Cerai',]);
            $table->enum('status_dalam_keluarga', ['Kepala Keluarga', 'Istri', 'Anak',]);
            $table->string('pendidikan');
            $table->string('pekerjaan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_anggota_keluarga');
    }
};
