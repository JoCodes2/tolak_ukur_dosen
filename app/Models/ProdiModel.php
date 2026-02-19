<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProdiModel extends Model
{
    use HasUuids, HasFactory;
    protected $table = 'program_studi';
    protected $fillable = ['id', 'nama_prodi', 'created_at', 'updated_at'];
}
