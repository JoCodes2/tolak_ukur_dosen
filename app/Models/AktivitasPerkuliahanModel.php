<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AktivitasPerkuliahanModel extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'aktivitas_perkuliahan';

    protected $fillable = [
        'id',
        'id_periode',
        'id_prodi',
        'id_kelas',
        'created_at',
        'updated_at'
    ];

    // RELASI MASTER
    public function periode()
    {
        return $this->belongsTo(PeriodeModel::class, 'id_periode');
    }

    public function prodi()
    {
        return $this->belongsTo(ProdiModel::class, 'id_prodi');
    }

    public function kelas()
    {
        return $this->belongsTo(KelasModel::class, 'id_kelas');
    }

    // RELASI DETAIL
    public function mengajarDetail()
    {
        return $this->hasMany(AktivitasMengajarDetailModel::class, 'id_aktivitas');
    }

    public function pesertaDetail()
    {
        return $this->hasMany(AktivitasPesertaDetailModel::class, 'id_aktivitas');
    }
}
