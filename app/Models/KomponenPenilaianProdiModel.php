<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class KomponenPenilaianProdiModel extends Model
{
    use HasUuids, HasUuids;
    protected $table = 'komponen_penilaian_prodi';
    protected $fillable = [
        'id',
        'id_prodi',
        'id_mk',
        'id_periode',
        'nama_komponen',
        'created_at',
        'updated_at'
    ];
}
