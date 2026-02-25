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
        Schema::create('aktivitas_perkuliahan', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('id_periode')
                ->constrained('periode')
                ->restrictOnDelete();

            $table->foreignUuid('id_prodi')
                ->constrained('program_studi')
                ->restrictOnDelete();

            $table->foreignUuid('id_kelas')
                ->constrained('kelas')
                ->restrictOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aktivitas_perkuliahan');
    }
};
