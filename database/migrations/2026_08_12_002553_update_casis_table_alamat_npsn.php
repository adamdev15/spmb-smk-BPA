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
        Schema::table('casis', function (Blueprint $table) {
            $table->dropColumn('npsn_sekolah');
            $table->string('rt', 10)->nullable()->after('alamat_siswa');
            $table->string('rw', 10)->nullable()->after('rt');
            $table->string('kecamatan')->nullable()->after('rw');
            $table->string('kab_kota')->nullable()->after('kecamatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('casis', function (Blueprint $table) {
            $table->string('npsn_sekolah')->nullable();
            $table->dropColumn(['rt', 'rw', 'kecamatan', 'kab_kota']);
        });
    }
};
