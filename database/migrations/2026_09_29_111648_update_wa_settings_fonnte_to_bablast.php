<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Migrate settings: rename Fonnte keys to Bablast keys.
     */
    public function up(): void
    {
        // 1. Rename fonnte_token -> bablast_api_token
        DB::table('settings')
            ->where('key', 'fonnte_token')
            ->update([
                'key'  => 'bablast_api_token',
                'name' => 'Bablast API Token',
                'value' => '', // reset agar admin isi ulang dengan token baru
            ]);

        // 2. Rename fonnte_status -> wa_status
        DB::table('settings')
            ->where('key', 'fonnte_status')
            ->update([
                'key'  => 'wa_status',
                'name' => 'Status WhatsApp Notifikasi',
            ]);

        // 3. Tambah setting bablast_sender_id jika belum ada
        if (DB::table('settings')->where('key', 'bablast_sender_id')->doesntExist()) {
            DB::table('settings')->insert([
                'key'        => 'bablast_sender_id',
                'name'       => 'Bablast Sender ID',
                'value'      => '',
                'type'       => 'text',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Rollback: kembalikan ke keys Fonnte lama.
     */
    public function down(): void
    {
        DB::table('settings')
            ->where('key', 'bablast_api_token')
            ->update([
                'key'  => 'fonnte_token',
                'name' => 'Fonnte Token',
            ]);

        DB::table('settings')
            ->where('key', 'wa_status')
            ->update([
                'key'  => 'fonnte_status',
                'name' => 'Status Fonnte',
            ]);

        DB::table('settings')->where('key', 'bablast_sender_id')->delete();
    }
};
