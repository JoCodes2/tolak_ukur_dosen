<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class BobotPenilaianDosenModel extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'bobot_penilaian_dosen';

    protected $fillable = [
        'id',
        'id_mengajar_detail',
        'id_komponen',
        'bobot',
        'created_at',
        'updated_at'
    ];

    public function mengajarDetail()
    {
        return $this->belongsTo(AktivitasMengajarDetailModel::class, 'id_mengajar_detail');
    }

    public function komponen()
    {
        return $this->belongsTo(KomponenPenilaianProdiModel::class, 'id_komponen');
    }

    public function nilai()
    {
        return $this->hasMany(NilaiModel::class, 'id_bobot');
    }
}
