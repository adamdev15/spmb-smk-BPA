<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    protected $table = 'provinsi';
    protected $primaryKey = 'kode_prov';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'kode_prov',
        'nama_provinsi',
    ];

    public function kabupatens()
    {
        return $this->hasMany(Kabupaten::class, 'kode_prov', 'kode_prov');
    }
}
