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
            $table->decimal('nilai_tpa', 5, 2)->nullable()->after('status_pendaftaran');
            $table->decimal('nilai_wawancara', 5, 2)->nullable()->after('nilai_tpa');
            $table->enum('status_kelulusan', ['Proses', 'Lulus', 'Tidak Lulus', 'Cadangan'])->default('Proses')->after('nilai_wawancara');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('casis', function (Blueprint $table) {
            $table->dropColumn(['nilai_tpa', 'nilai_wawancara', 'status_kelulusan']);
        });
    }
};