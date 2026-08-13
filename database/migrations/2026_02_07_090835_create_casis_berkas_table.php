<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('casis_berkas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('casis_id')->constrained('casis')->onDelete('cascade');
            $table->string('nama_berkas');
            $table->text('path');
            $table->string('extension');
            $table->integer('size');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('casis_berkas');
    }
};