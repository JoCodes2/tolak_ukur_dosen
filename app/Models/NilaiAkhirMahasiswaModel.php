<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiAkhirMahasiswaModel extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'nilai_akhir';

    // Karena di migration tidak ada created_at, kita matikan standar timestamps
    // atau definisikan secara manual jika hanya ingin updated_at
    public $timestamps = false;

    protected $fillable = [
        'id',
        'id_peserta',
        'id_mk',
        'nilai_angka',
        'nilai_huruf',
        'bobot_mutu',
        'status',
        'updated_at',
        'created_at'
    ];


    protected $casts = [
        'nilai_angka' => 'float',
        'bobot_mutu'  => 'float',
        'updated_at'  => 'datetime',
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
