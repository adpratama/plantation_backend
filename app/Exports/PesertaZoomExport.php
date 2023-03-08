<?php

namespace App\Exports;

use App\Models\PesertaZoom;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;

class PesertaZoomExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $sesi_id;
    function __construct ($sesi_id) {
        $this->sesi  = $sesi_id;
    }

    public function collection()
    {
        return DB::table('peserta_zoom')->leftJoin('sesi_zoom', 'peserta_zoom.sesi_zoom_id', 'sesi_zoom.id')
        ->where('peserta_zoom.sesi_zoom_id', $this->sesi)
        ->orderBy('peserta_zoom.sesi_zoom_id', 'asc')
        ->select('sesi_zoom.id as sesi_id', 'sesi_zoom.nama as sesi_nama', 'sesi_zoom.sesi', 'peserta_zoom.id', 'peserta_zoom.nama', 'peserta_zoom.email', 'peserta_zoom.hp')
        ->get();
    }
}
