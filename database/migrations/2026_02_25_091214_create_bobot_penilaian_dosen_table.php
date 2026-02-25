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
        Schema::create('bobot_penilaian_dosen', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('id_mengajar_detail')
                ->constrained('aktivitas_mengajar_detail')
                ->restrictOnDelete();

            $table->foreignUuid('id_komponen')
                ->constrained('komponen_penilaian_prodi')
                ->restrictOnDelete();

            $table->float('bobot');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bobot_penilaian_dosen');
    }
};
