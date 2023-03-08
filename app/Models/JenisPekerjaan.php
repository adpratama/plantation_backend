<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisPekerjaan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jenis_pekerjaan';

    protected $fillable = [
        'nama',
        'status'
    ];


}
