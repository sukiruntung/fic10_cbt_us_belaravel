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
        Schema::create('ujian_soal_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ujians_id')->constrained('ujians')->onDelete('restrict');
            $table->foreignId('soals_id')->constrained('soals')->onDelete('restrict');
            $table->boolean('kebenaran')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ujian_soal_lists');
    }
};
