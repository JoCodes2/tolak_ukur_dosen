<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KelasModel extends Model
{
    use HasUuids, HasFactory;
    protected $table = 'kelas';
    protected $fillable = ['id', 'nama_kelas', 'created_at', 'updated_at'];
}
