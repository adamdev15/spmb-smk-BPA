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
        Schema::table('casis', function (Blueprint $table) {
            $table->enum('sumber_pendaftaran', ['online', 'offline'])->default('online')->after('no_pendaftaran');
            $table->foreignId('jurusan_id')->nullable()->after('no_hp_siswa')->constrained('master_jurusan')->nullOnDelete();
            $table->foreignId('program_keunggulan_id')->nullable()->after('jurusan_id')->constrained('program_keunggulan')->nullOnDelete();
            
            // Selection Physical & Psychological Tests
            $table->enum('hasil_psikotes', ['Belum Tes', 'Lulus', 'Tidak Lulus'])->default('Belum Tes')->after('status_kelulusan');
            $table->text('catatan_psikotes')->nullable()->after('hasil_psikotes');
            $table->enum('tes_tindik', ['Belum Periksa', 'Memenuhi', 'Tidak Memenuhi'])->default('Belum Periksa')->after('catatan_psikotes');
            $table->enum('tes_tato', ['Belum Periksa', 'Memenuhi', 'Tidak Memenuhi'])->default('Belum Periksa')->after('tes_tindik');
            $table->enum('tes_buta_warna', ['Belum Periksa', 'Normal', 'Parsial', 'Total'])->default('Belum Periksa')->after('tes_tato');
            
            // Re-enrollment Status
            $table->enum('status_daftar_ulang', ['Belum', 'Sudah'])->default('Belum')->after('tes_buta_warna');
            $table->timestamp('tgl_daftar_ulang')->nullable()->after('status_daftar_ulang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('casis', function (Blueprint $table) {
            $table->dropForeign(['jurusan_id']);
            $table->dropForeign(['program_keunggulan_id']);
            $table->dropColumn([
                'sumber_pendaftaran',
                'jurusan_id',
                'program_keunggulan_id',
                'hasil_psikotes',
                'catatan_psikotes',
                'tes_tindik',
                'tes_tato',
                'tes_buta_warna',
                'status_daftar_ulang',
                'tgl_daftar_ulang'
            ]);
        });
    }
};
