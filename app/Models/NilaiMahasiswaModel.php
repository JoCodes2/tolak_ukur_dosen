<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiMahasiswaModel extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'nilai';

    protected $fillable = [
        'id',
        'id_peserta',
        'id_bobot',
        'nilai',
        'created_at',
        'updated_at'
    ];

    public function peserta()
    {
        return $this->belongsTo(AktivitasPesertaDetailModel::class, 'id_peserta');
    }

    public function bobotPenilaian()
    {
        return $this->belongsTo(BobotPenilaianDosenModel::class, 'id_bobot');
    }
}
