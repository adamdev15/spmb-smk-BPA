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
        Schema::create('casis', function (Blueprint $table) {
            $table->id();
            $table->string('no_pendaftaran')->unique();
            $table->string('password'); // Will be hashed
            $table->string('nisn')->unique();
            $table->string('nik')->nullable();
            $table->string('no_kk')->nullable();
            $table->string('nama_lengkap');
            $table->enum('jk', ['L', 'P']);
            $table->string('tempat_lahir');
            $table->date('tgl_lahir');
            $table->integer('anak_ke')->nullable();
            $table->integer('jml_saudara')->nullable();
            $table->string('agama');
            $table->string('status_keluarga')->nullable();
            $table->text('alamat_siswa');
            $table->string('no_hp_siswa');

            // FKs to Master Data
            // Using ID implies unsigned big integer, matching standard Laravel IDs
            $table->foreignId('ketrampilan_id')->nullable()->constrained('master_ketrampilan');
            $table->foreignId('hobi_id')->nullable()->constrained('master_hobi');
            $table->foreignId('cita_id')->nullable()->constrained('master_cita');

            // Ayah
            $table->string('nik_ayah')->nullable();
            $table->string('nama_ayah')->nullable();
            $table->date('tgl_lahir_ayah')->nullable();
            $table->text('alamat_ayah')->nullable();
            $table->foreignId('pendidikan_ayah_id')->nullable()->constrained('master_pendidikan');
            $table->foreignId('pekerjaan_ayah_id')->nullable()->constrained('master_pekerjaan');
            $table->foreignId('penghasilan_ayah_id')->nullable()->constrained('master_penghasilan');
            $table->string('no_hp_ayah')->nullable();
            $table->string('status_ayah')->nullable(); // Hidup/Meninggal

            // Ibu
            $table->string('nik_ibu')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->date('tgl_lahir_ibu')->nullable();
            $table->text('alamat_ibu')->nullable();
            $table->foreignId('pendidikan_ibu_id')->nullable()->constrained('master_pendidikan');
            $table->foreignId('pekerjaan_ibu_id')->nullable()->constrained('master_pekerjaan');
            $table->foreignId('penghasilan_ibu_id')->nullable()->constrained('master_penghasilan');
            $table->string('no_hp_ibu')->nullable();
            $table->string('status_ibu')->nullable();

            // Wali
            $table->string('nik_wali')->nullable();
            $table->string('nama_wali')->nullable();
            $table->date('tgl_lahir_wali')->nullable();
            $table->text('alamat_wali')->nullable();
            $table->foreignId('pendidikan_wali_id')->nullable()->constrained('master_pendidikan');
            $table->foreignId('pekerjaan_wali_id')->nullable()->constrained('master_pekerjaan');
            $table->foreignId('penghasilan_wali_id')->nullable()->constrained('master_penghasilan');
            $table->string('no_hp_wali')->nullable();
            $table->string('status_wali')->nullable();
            $table->foreignId('orientasi_ortu_id')->nullable()->constrained('master_orientasi_ortu');

            // Sekolah Asal
            $table->enum('jenis_sekolah', ['SMP', 'MTS'])->nullable();
            $table->string('npsn_sekolah')->nullable();
            $table->string('nama_sekolah')->nullable();
            $table->enum('status_sekolah', ['NEGERI', 'SWASTA'])->nullable();
            $table->text('alamat_sekolah')->nullable();
            $table->enum('akreditasi_sekolah', ['A', 'B', 'C', 'TIDAK TERAKREDITASI'])->nullable();

            // Status System
            $table->string('status_verifikasi')->default('Belum Diverifikasi');
            $table->string('status_pendaftaran')->default('Draft');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('casis');
    }
};