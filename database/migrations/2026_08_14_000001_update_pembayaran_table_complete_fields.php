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
        Schema::table('pembayaran', function (Blueprint $table) {
            if (!Schema::hasColumn('pembayaran', 'jenis_pembayaran')) {
                $table->string('jenis_pembayaran')->default('daftar_ulang')->after('order_id');
            }
            if (!Schema::hasColumn('pembayaran', 'transaction_id')) {
                $table->string('transaction_id')->nullable()->after('payment_type');
            }
            if (!Schema::hasColumn('pembayaran', 'payment_gateway')) {
                $table->string('payment_gateway')->nullable()->default('midtrans')->after('transaction_id');
            }
            if (!Schema::hasColumn('pembayaran', 'tgl_jatuh_tempo')) {
                $table->date('tgl_jatuh_tempo')->nullable()->after('settlement_time');
            }
            if (!Schema::hasColumn('pembayaran', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('casis_id')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('pembayaran', 'raw_response')) {
                $table->longText('raw_response')->nullable()->after('catatan_admin');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            if (Schema::hasColumn('pembayaran', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
            $columns = ['jenis_pembayaran', 'transaction_id', 'payment_gateway', 'tgl_jatuh_tempo', 'raw_response'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('pembayaran', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};