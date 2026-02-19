<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KrsModel extends Model
{
    use HasUuids, HasFactory;
    protected $table = 'krs';
    protected $fillable = ['id', 'id_mahasiswa', 'id_aktivitas', 'created_at', 'updated_at'];

    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaModel::class, 'id_mahasiswa');
    }
    public function aktivitas()
    {
        return $this->belongsTo(AktivitasPerkuliahanModel::class, 'id_aktivitas');
    }
    public function nilai()
    {
        return $this->hasMany(NilaiModel::class, 'id_krs');
    }
    public function nilaiAkhir()
    {
        return $this->hasOne(NilaiAkhirModel::class, 'id_krs');
    }
}
