<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id('id_admin');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('nama_lengkap');
            $table->string('no_handphone');
            $table->enum('role', ['Ketua Blok', 'Ketua RT', 'Ketua Bagian', 'Wakil Ketua RT', 'Sekretaris RT', 'Bendahara RT']);

            // 1. Ganti dari 'blok' ke 'id_blok'
            // 2. Tipe datanya HARUS sama dengan $table->id('id_blok') di tabel blok
            // 3. Kita HAPUS foreign key dari sini.
            $table->unsignedBigInteger('id_blok')->nullable(); 

            $table->string('bagian')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};