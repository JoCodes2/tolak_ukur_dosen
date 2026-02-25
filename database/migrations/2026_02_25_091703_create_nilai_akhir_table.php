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
        Schema::create('nilai_akhir', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('id_peserta')
                ->constrained('aktivitas_peserta_detail')
                ->restrictOnDelete();

            $table->foreignUuid('id_mk')
                ->constrained('mata_kuliah')
                ->restrictOnDelete();

            $table->float('nilai_angka');
            $table->string('nilai_huruf', 2);
            $table->float('bobot_mutu');
            $table->enum('status', ['draft', 'final']);
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_akhir');
    }
};
