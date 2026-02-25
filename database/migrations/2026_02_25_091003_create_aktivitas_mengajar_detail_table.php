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
        Schema::create('aktivitas_mengajar_detail', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('id_aktivitas')
                ->constrained('aktivitas_perkuliahan')
                ->restrictOnDelete();

            $table->foreignUuid('id_mk')
                ->constrained('mata_kuliah')
                ->restrictOnDelete();

            $table->foreignUuid('id_dosen')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aktivitas_mengajar_detail');
    }
};
