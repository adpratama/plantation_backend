<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisPupuk extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jenis_pupuk';

    protected $fillable = [
        'nama',
        'status'
    ];
}
