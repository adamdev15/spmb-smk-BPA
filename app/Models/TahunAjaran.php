<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'status'];

    public function spmbPeriods()
    {
        return $this->hasMany(SpmbPeriod::class);
    }
}
