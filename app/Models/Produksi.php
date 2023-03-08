<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produksi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'produksi';

    protected $fillable = [
        'user_id',
        'tanggal',
        'estate',
        'divisi',
        'blok',
        'pokok',
        'tahun',
        'berat',
        'jenjang',
    ];

    public function user ()
    {
     	return $this->belongsTo(User::class, 'user_id');
    }

}
