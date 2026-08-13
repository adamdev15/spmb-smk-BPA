<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramKeunggulan extends Model
{
    use HasFactory;

    protected $table = 'program_keunggulan';
    protected $guarded = ['id'];

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }

    public function casis()
    {
        return $this->hasMany(Casis::class, 'program_keunggulan_id');
    }
}
