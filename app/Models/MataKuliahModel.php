<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MataKuliahModel extends Model
{
    use HasUuids, HasFactory;
    protected $table = 'mata_kuliah';
    protected $fillable = ['id', 'kode_mk', 'nama_mk', 'sks', 'created_at', 'updated_at'];
}
