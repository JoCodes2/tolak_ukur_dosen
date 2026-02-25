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
        Schema::create('nilai', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('id_peserta')
                ->constrained('aktivitas_peserta_detail')
                ->restrictOnDelete();

            $table->foreignUuid('id_bobot')
                ->constrained('bobot_penilaian_dosen')
                ->restrictOnDelete();

            $table->float('nilai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai');
    }
};
