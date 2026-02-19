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
        Schema::create('komponen_penilaian_prodi', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('id_prodi')->constrained('program_studi');
            $table->foreignUuid('id_mk')->constrained('mata_kuliah');
            $table->foreignUuid('id_periode')->constrained('periode');
            $table->enum('nama_komponen', ['Tugas', 'UTS', 'UAS', 'Kehadiran']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('komponen_penilaian_prodi');
    }
};
