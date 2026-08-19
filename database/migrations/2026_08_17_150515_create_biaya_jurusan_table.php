<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('biaya_jurusan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('biaya_id')->constrained()->cascadeOnDelete();
            $table->foreignId('jurusan_id')->constrained('master_jurusan')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('biaya_jurusan');
    }
};
