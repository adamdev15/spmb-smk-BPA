<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('jadwal_spmb');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('jadwal_spmb', function (Blueprint $table) {
            $table->id();
            $table->string('tahap_nama');
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->text('keterangan')->nullable();
            $table->integer('urutan')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
};
