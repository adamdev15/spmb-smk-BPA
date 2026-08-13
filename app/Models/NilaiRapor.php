<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiRapor extends Model
{
    use HasFactory;

    protected $table = 'nilai_rapor';
    protected $guarded = ['id_nilai'];
    protected $primaryKey = 'id_nilai';

    public function casis()
    {
        return $this->belongsTo(Casis::class , 'casis_id');
    }
}