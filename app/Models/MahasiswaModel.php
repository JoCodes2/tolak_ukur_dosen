<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MahasiswaModel extends Model
{
    use HasUuids, HasFactory;
    protected $table = 'mahasiswa';
    protected $fillable = ['id', 'nim', 'nama', 'angkatan', 'id_prodi', 'created_at', 'updated_at'];

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(ProdiModel::class, 'id_prodi', 'id');
    }
}
