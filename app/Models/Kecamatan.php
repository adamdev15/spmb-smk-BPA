<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $table = 'kecamatan';
    protected $primaryKey = 'kode_kec';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'kode_kec',
        'nama_kec',
        'kode_kabkota',
        'kode_prov',
    ];

    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class, 'kode_kabkota', 'kode_kabkota');
    }

    public function kelurahans()
    {
        return $this->hasMany(Kelurahan::class, 'kode_kec', 'kode_kec');
    }
}
