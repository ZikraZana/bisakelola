<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Bagian 1: Membuat tabel 'blok'
        Schema::create('blok', function (Blueprint $table) {
            $table->id('id_blok');
            $table->string('nama_blok');
            
            // Ini berhasil karena tabel 'admins' sudah ada dari migrasi pertama
            $table->foreignId('id_admin')->nullable()->constrained('admins')->onDelete('set null'); 
            
            $table->timestamps();
        });

        // Bagian 2: Memodifikasi tabel 'admins' untuk menambahkan foreign key
        // Ini berhasil karena tabel 'blok' BARU SAJA dibuat di atas
        Schema::table('admins', function (Blueprint $table) {
            $table->foreign('id_blok')
                  ->references('id_blok')
                  ->on('blok')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        // Hapus foreign key di 'admins' dulu
        Schema::table('admins', function (Blueprint $table) {
            $table->dropForeign(['id_blok']);
        });

        // Baru hapus tabel 'blok'
        Schema::dropIfExists('blok');
    }
};