<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Perawatan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'perawatan';

    protected $fillable = [
        'user_id',
        'tanggal',
        'estate',
        'divisi',
        'blok',
        'pokok',
        'jenis_pekerjaan_id',
        'tahun',
        'luas',
        'rotasi'
    ];
}
