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
        Schema::create('master_ketrampilan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->timestamps();
        });

        Schema::create('master_hobi', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->timestamps();
        });

        Schema::create('master_cita', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->timestamps();
        });

        Schema::create('master_orientasi_ortu', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->timestamps();
        });

        Schema::create('master_pendidikan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->timestamps();
        });

        Schema::create('master_pekerjaan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('target', ['ayah', 'ibu', 'wali', 'semua'])->default('semua');
            $table->timestamps();
        });

        Schema::create('master_penghasilan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_penghasilan');
        Schema::dropIfExists('master_pekerjaan');
        Schema::dropIfExists('master_pendidikan');
        Schema::dropIfExists('master_orientasi_ortu');
        Schema::dropIfExists('master_cita');
        Schema::dropIfExists('master_hobi');
        Schema::dropIfExists('master_ketrampilan');
    }
};
