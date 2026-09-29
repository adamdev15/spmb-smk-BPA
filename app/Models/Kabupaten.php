<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kabupaten extends Model
{
    protected $table = 'kabupaten';
    protected $primaryKey = 'kode_kabkota';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'kode_kabkota',
        'nama_kabkota',
        'kode_prov',
    ];

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'kode_prov', 'kode_prov');
    }

    public function kecamatans()
    {
        return $this->hasMany(Kecamatan::class, 'kode_kabkota', 'kode_kabkota');
    }
}
