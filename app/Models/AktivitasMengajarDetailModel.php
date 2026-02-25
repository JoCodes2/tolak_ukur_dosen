<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AktivitasMengajarDetailModel extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'aktivitas_mengajar_detail';

    protected $fillable = [
        'id',
        'id_aktivitas',
        'id_mk',
        'id_dosen',
        'created_at',
        'updated_at'
    ];

    public function aktivitas()
    {
        return $this->belongsTo(AktivitasPerkuliahanModel::class, 'id_aktivitas');
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliahModel::class, 'id_mk');
    }

    public function dosen()
    {
        return $this->belongsTo(User::class, 'id_dosen');
    }

    public function bobotPenilaian()
    {
        return $this->hasMany(BobotPenilaianDosenModel::class, 'id_mengajar_detail');
    }
}
