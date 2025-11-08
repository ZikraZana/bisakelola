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
        Schema::create('data_keluarga', function (Blueprint $table) {
            $table->id('id_keluarga');
            $table->string('no_kk')->unique();

            // foreign key
            $table->foreignId('id_admin')->constrained('admins', 'id_admin');
            $table->foreignId('id_blok')->constrained('blok', 'id_blok');
            $table->foreignId('id_desil')->constrained('desil', 'id_desil');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_keluarga');
    }
};
