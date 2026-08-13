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
    ];

    public function casis()
    {
        return $this->belongsTo(Casis::class, 'casis_id');
    }
}
