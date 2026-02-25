<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AktivitasPesertaDetailModel extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'aktivitas_peserta_detail';

    protected $fillable = [
        'id',
        'id_aktivitas',
        'id_mahasiswa',
        'created_at',
        'updated_at'
    ];

    public function aktivitas()
    {
        return $this->belongsTo(AktivitasPerkuliahanModel::class, 'id_aktivitas');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaModel::class, 'id_mahasiswa');
    }

    public function nilai()
    {
        return $this->hasMany(NilaiModel::class, 'id_peserta');
    }

    public function nilaiAkhir()
    {
        return $this->hasOne(NilaiAkhirModel::class, 'id_peserta');
    }
}
