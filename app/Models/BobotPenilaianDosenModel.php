<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class BobotPenilaianDosenModel extends Model
{
    use HasUuids, HasUuids;
    protected $table = 'bobot_penilaian_dosen';
    protected $fillable = [
        'id',
        'id_aktivitas',
        'id_komponen',
        'bobot',
        'created_at',
        'updated_at'
    ];

    public function komponenProdi()
    {
        return $this->belongsTo(KomponenPenilaianProdiModel::class, 'id_komponen');
    }
    public function aktivitas()
    {
        return $this->belongsTo(AktivitasPerkuliahanModel::class, 'id_aktivitas');
    }
}
