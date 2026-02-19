<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NilaiModel extends Model
{
    use HasUuids, HasFactory;
    protected $table = 'nilai';
    protected $fillable = ['id', 'id_krs', 'id_bobot', 'nilai', 'created_at', 'updated_at'];

    public function krs()
    {
        return $this->belongsTo(KrsModel::class, 'id_krs');
    }
    public function bobotDosen()
    {
        return $this->belongsTo(BobotPenilaianDosenModel::class, 'id_bobot');
    }
}
