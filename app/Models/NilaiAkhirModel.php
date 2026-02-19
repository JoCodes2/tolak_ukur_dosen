<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class NilaiAkhirModel extends Model
{
    use HasUuids, HasUuids;
    protected $table = 'nilai_akhir';
    protected $fillable = ['id', 'id_krs', 'nilai_angka', 'nilai_huruf', 'status', 'created_at', 'updated_at'];

    public function krs()
    {
        return $this->belongsTo(KrsModel::class, 'id_krs');
    }
}
