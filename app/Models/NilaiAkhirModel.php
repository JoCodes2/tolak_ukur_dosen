<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class NilaiAkhirModel extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'nilai_akhir';

    protected $fillable = [
        'id',
        'id_peserta',
        'id_mk',
        'nilai_angka',
        'nilai_huruf',
        'bobot_mutu',
        'status',
        'created_at',
        'updated_at'
    ];

    public function peserta()
    {
        return $this->belongsTo(AktivitasPesertaDetailModel::class, 'id_peserta');
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliahModel::class, 'id_mk');
    }
}
