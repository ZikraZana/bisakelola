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
        Schema::create('data_penerima_bansos', function (Blueprint $table) {
            $table->id('id_penerima_bansos');

            $table->foreignId('id_keluarga')->constrained('data_keluarga', 'id_keluarga')->onDelete('cascade');
            $table->foreignId('id_bansos')->nullable()->constrained('bansos', 'id_bansos')->onDelete('set null');

            // --- DIUBAH DI SINI ---
            // Asumsi PK di tabel 'admins' adalah 'id_admin' (berdasarkan DataKeluargaController Anda)
            $table->foreignId('id_admin_pengaju')->constrained('admins', 'id_admin')->onDelete('cascade');
            $table->foreignId('id_admin_penyetuju')->nullable()->constrained('admins', 'id_admin')->onDelete('set null');

            // Kolom lainnya
            $table->text('keterangan_pengajuan')->nullable();
            $table->string('periode')->nullable();
            $table->string('status_acc')->default('Diajukan');
            $table->text('keterangan_acc')->nullable();
            $table->string('status_bansos_diterima')->default('Belum');
            $table->date('tanggal_pengambilan_bansos')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_penerima_bansos');
    }
};
