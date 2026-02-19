<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AktivitasPerkuliahanModel extends Model
{
    use HasUuids, HasUuids;
    protected $table = 'aktivitas_perkuliahan';
    protected $fillable = [
        'id',
        'id_prodi',
        'id_mk',
        'id_kelas',
        'id_dosen',
        'id_periode',
        'created_at',
        'updated_at'
    ];

    public function dosen()
    {
        return $this->belongsTo(User::class, 'id_dosen');
    }
    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliahModel::class, 'id_mk');
    }
    public function kelas()
    {
        return $this->belongsTo(KelasModel::class, 'id_kelas');
    }
    public function periode()
    {
        return $this->belongsTo(PeriodeModel::class, 'id_periode');
    }
    public function bobotDosen()
    {
        return $this->hasMany(BobotPenilaianDosenModel::class, 'id_aktivitas');
    }
    public function krs()
    {
        return $this->hasMany(KrsModel::class, 'id_aktivitas');
    }
}
