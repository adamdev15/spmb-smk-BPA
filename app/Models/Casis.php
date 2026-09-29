<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Casis extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'casis';
    protected $guarded = ['id'];

    public function spmbPeriod()
    {
        return $this->belongsTo(SpmbPeriod::class);
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }

    public function programKeunggulan()
    {
        return $this->belongsTo(ProgramKeunggulan::class, 'program_keunggulan_id');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'casis_id');
    }

    public function pembayaranTerakhir()
    {
        return $this->hasOne(Pembayaran::class, 'casis_id')->latestOfMany();
    }

    public function pembayaranDaftarUlang()
    {
        return $this->hasOne(Pembayaran::class, 'casis_id')->where('jenis_pembayaran', 'daftar_ulang')->latestOfMany();
    }

    public function nilaiRapor()
    {
        return $this->hasMany(NilaiRapor::class, 'casis_id');
    }

    public function berkas()
    {
        return $this->hasMany(CasisBerkas::class, 'casis_id');
    }

    public function isVerified(): bool
    {
        return $this->status_verifikasi === 'Diverifikasi' || $this->status_kelulusan === 'Lulus';
    }

    public function isLunasDaftarUlang(): bool
    {
        return $this->status_daftar_ulang === 'Sudah';
    }

    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class, 'id_kelurahan', 'kode_desa_kel');
    }

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'id_provinsi', 'kode_prov');
    }

    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class, 'id_kabupaten', 'kode_kabkota');
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'id_kecamatan', 'kode_kec');
    }
}