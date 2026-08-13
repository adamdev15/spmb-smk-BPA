<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CasisBerkas extends Model
{
    protected $table = 'casis_berkas';
    protected $guarded = ['id'];

    public function casis()
    {
        return $this->belongsTo(Casis::class , 'casis_id');
    }
}