<?php

namespace App\Exports;

use App\Models\Administrasi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;

class AdministrasiExport implements FromCollection
{
     /**
    * @return \Illuminate\Support\Collection
    */
    public $periode_id;
    public $program_id;
    
    function __construct ($periode_id, $program_id) {
        $this->periode_id  = $periode_id;
        $this->program_id = $program_id;
    }

    public function collection()
    {
        return DB::table('administrasi')
            ->leftJoin('periode', 'administrasi.periode_id', 'periode.id')
            ->leftJoin('program', 'administrasi.program_id', 'program.id')
            ->leftJoin('pengajuan', 'administrasi.pengajuan_fbk_id', 'pengajuan.id')
            ->leftJoin('pengajuan_danaindonesiana', 'administrasi.pengajuan_danaindonesiana_id', 'pengajuan_danaindonesiana.id')
            ->leftJoin('users', 'administrasi.user_id', 'users.id')
            ->where([['administrasi.periode_id', $this->periode_id], ['administrasi.program_id', $this->program_id]])
            ->orderBy('administrasi.id', 'asc')
        ->select('administrasi.id', 'users.name as nama_akun', 'users.email as email_akun', 'periode.tahun', 'program.nama as program', 'pengajuan.id as id_fbk', 'pengajuan_danaindonesiana.id as id_danaindonesiana', 'administrasi.surat_pengajuan', 'administrasi.proposal', 'administrasi.profil', 'administrasi.surat_pengajuan', 'administrasi.pertanggungjawaban', 'administrasi.kesanggupan', 'administrasi.integritas', 'administrasi.politik', 'administrasi.domisili', 'administrasi.akta', 'administrasi.rekening', 'administrasi.npwp', 'administrasi.ktp', 'administrasi.foto_sekretariat', 'administrasi.rab', 'administrasi.sertifikat', 'administrasi.rekomendasi', 'administrasi.perjanjian', 'administrasi.status', 'administrasi.updated_at')
        ->get();
    }
}
