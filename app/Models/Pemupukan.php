<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pemupukan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pemupukan';

    protected $fillable = [
        'user_id',
        'tanggal',
        'estate',
        'divisi',
        'blok',
        'pokok',
        'jenis_pupuk_id',
        'tahun',
        'luas'
    ];
}
