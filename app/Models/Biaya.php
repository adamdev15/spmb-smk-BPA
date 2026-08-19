<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Biaya extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_biaya',
        'jenis_biaya',
        'nominal',
    ];

    public function jurusans()
    {
        return $this->belongsToMany(Jurusan::class, 'biaya_jurusan', 'biaya_id', 'jurusan_id');
    }
}
