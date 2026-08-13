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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('casis_id')->constrained('casis')->cascadeOnDelete();
            $table->string('order_id')->unique();
            $table->enum('tipe_pembayaran', ['online', 'offline'])->default('online');
            $table->decimal('nominal', 12, 2);
            $table->string('snap_token')->nullable();
            $table->enum('transaction_status', ['pending', 'settlement', 'expire', 'cancel', 'failed'])->default('pending');
            $table->string('payment_type')->nullable();
            $table->timestamp('settlement_time')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
