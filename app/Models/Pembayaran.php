<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';
    protected $guarded = ['id'];

    protected $casts = [
        'settlement_time' => 'datetime',
        'tgl_jatuh_tempo' => 'date',
        'nominal' => 'decimal:2',
    ];

    public function casis()
    {
        return $this->belongsTo(Casis::class, 'casis_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isSettlement(): bool
    {
        return in_array($this->transaction_status, ['settlement', 'success', 'capture']);
    }

    public function isPending(): bool
    {
        return $this->transaction_status === 'pending';
    }

    public function isExpired(): bool
    {
        return in_array($this->transaction_status, ['expire', 'expired', 'cancel', 'failed']);
    }

    public function getFormattedNominalAttribute(): string
    {
        return 'Rp ' . number_format($this->nominal, 0, ',', '.');
    }
}