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
        Schema::create('nilai_rapor', function (Blueprint $table) {
            $table->id('id_nilai');
            $table->foreignId('casis_id')->constrained('casis')->onDelete('cascade');
            $table->enum('semester', ['3', '4', '5']);
            $table->decimal('ipa', 5, 2)->default(0);
            $table->decimal('ips', 5, 2)->default(0);
            $table->decimal('matematika', 5, 2)->default(0);
            $table->decimal('bind', 5, 2)->default(0);
            $table->decimal('bing', 5, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_rapor');
    }
};