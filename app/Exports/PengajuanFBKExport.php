<?php

namespace App\Exports;

use App\Models\Pengajuan;
use App\Models\Periode;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;

class PengajuanFBKExport implements FromCollection
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
        return DB::table('pengajuan')
            ->leftJoin('periode', 'pengajuan.periode_id', 'periode.id')
            ->leftJoin('program', 'pengajuan.program_id', 'program.id')
            ->leftJoin('users', 'pengajuan.user_id', 'users.id')
            ->leftJoin('provinces', 'pengajuan.provinsi_id', 'provinces.id')
            ->leftJoin('regencies', 'pengajuan.kota_id', 'regencies.id')
            ->leftJoin('districts', 'pengajuan.kecamatan_id', 'districts.id')
            ->leftJoin('villages', 'pengajuan.desa_id', 'villages.id')
            ->where([['pengajuan.periode_id', $this->periode_id], ['pengajuan.program_id', $this->program_id]])
            ->orderBy('pengajuan.id', 'asc')
        ->select('pengajuan.id', 'users.name as nama_akun', 'users.email as email_akun', 'periode.tahun', 'program.nama as program', 'pengajuan.nama', 'pengajuan.jenis', 'pengajuan.kategori', 'pengajuan.nama_narahubung', 'pengajuan.jabatan_narahubung', 'pengajuan.hp_narahubung', 'pengajuan.wa_narahubung', 'pengajuan.email', 'provinces.name as provinsi', 'regencies.name as kota', 'districts.name as kecamatan', 'villages.name as kelurahan', 'pengajuan.alamat', 'pengajuan.nama_kegiatan', 'pengajuan.deskripsi_kegiatan', 'pengajuan.output_kegiatan', 'pengajuan.penerima_manfaat', 'pengajuan.biaya', 'pengajuan.kenapa', 'pengajuan.ktp', 'pengajuan.surat_undangan', 'pengajuan.surat_tugas', 'pengajuan.rab', 'pengajuan.pembiayaan', 'pengajuan.linimasa', 'pengajuan.cv', 'pengajuan.profil', 'pengajuan.data_pendukung', 'pengajuan.video', 'pengajuan.status', 'pengajuan.updated_at')
        ->get();
    }
}
