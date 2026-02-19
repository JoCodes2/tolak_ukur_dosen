<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MahasiswaModel extends Model
{
    use HasUuids, HasFactory;
    protected $table = 'mahasiswa';
    protected $fillable = ['id', 'nim', 'nama', 'angkatan', 'created_at', 'updated_at'];

    public function krs()
    {
        return $this->hasMany(KrsModel::class, 'id_mahasiswa');
    }
}
