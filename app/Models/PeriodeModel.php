<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PeriodeModel extends Model
{
    use HasUuids, HasFactory;
    protected $table = 'periode';
    protected $fillable = ['id', 'nama', 'semester', 'tahun_ajaran', 'status', 'created_at', 'updated_at'];
}
