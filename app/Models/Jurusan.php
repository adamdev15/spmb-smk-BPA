<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    use HasFactory;

    protected $table = 'master_jurusan';
    protected $guarded = ['id'];

    public function casis()
    {
        return $this->hasMany(Casis::class, 'jurusan_id');
    }

    public function programKeunggulan()
    {
        return $this->hasMany(ProgramKeunggulan::class, 'jurusan_id');
    }

    // Quota calculation: Remaining quota = Total Quota - Total students already re-enrolled (Sudah Daftar Ulang)
    public function getJumlahDaftarUlangAttribute()
    {
        return $this->casis()->where('status_daftar_ulang', 'Sudah')->count();
    }

    public function getSisaKuotaAttribute()
    {
        return max(0, $this->kuota - $this->jumlah_daftar_ulang);
    }

    public function biayas()
    {
        return $this->belongsToMany(Biaya::class, 'biaya_jurusan', 'jurusan_id', 'biaya_id');
    }
}
