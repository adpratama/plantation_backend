<?php

namespace App\Exports;

use App\Models\PengajuanDanaindonesiana;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;

class PengajuanDanaindonesianaExport implements FromCollection
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
        return DB::table('pengajuan_danaindonesiana')
            ->leftJoin('periode', 'pengajuan_danaindonesiana.periode_id', 'periode.id')
            ->leftJoin('users', 'pengajuan_danaindonesiana.user_id', 'users.id')
            ->leftJoin('provinces', 'pengajuan_danaindonesiana.provinsi_id', 'provinces.id')
            ->leftJoin('regencies', 'pengajuan_danaindonesiana.kota_id', 'regencies.id')
            ->leftJoin('districts', 'pengajuan_danaindonesiana.kecamatan_id', 'districts.id')
            ->leftJoin('villages', 'pengajuan_danaindonesiana.kelurahan_id', 'villages.id')
            ->where([['pengajuan_danaindonesiana.periode_id', $this->periode_id], ['pengajuan_danaindonesiana.program_id', $this->program_id]])
            ->orderBy('pengajuan_danaindonesiana.id', 'asc')
            ->select('pengajuan_danaindonesiana.id', 'users.name as nama_akun', 'users.email as email_akun', 'periode.tahun', 'pengajuan_danaindonesiana.nama', 'pengajuan_danaindonesiana.jenis', 'pengajuan_danaindonesiana.kategori', 'pengajuan_danaindonesiana.nama_narahubung', 'pengajuan_danaindonesiana.jabatan_narahubung', 'pengajuan_danaindonesiana.hp_narahubung', 'pengajuan_danaindonesiana.wa_narahubung', 'pengajuan_danaindonesiana.email', 'provinces.name as provinsi', 'regencies.name as kota', 'districts.name as kecamatan', 'villages.name as kelurahan', 'pengajuan_danaindonesiana.alamat', 'pengajuan_danaindonesiana.nama_kegiatan', 'pengajuan_danaindonesiana.deskripsi_kegiatan', 'pengajuan_danaindonesiana.output_kegiatan', 'pengajuan_danaindonesiana.penerima_manfaat', 'pengajuan_danaindonesiana.biaya', 'pengajuan_danaindonesiana.kenapa', 'pengajuan_danaindonesiana.rab', 'pengajuan_danaindonesiana.linimasa', 'pengajuan_danaindonesiana.profil', 'pengajuan_danaindonesiana.video', 'pengajuan_danaindonesiana.status', 'pengajuan_danaindonesiana.dana_pendamping', 'pengajuan_danaindonesiana.sumber_dana_pendamping', 'pengajuan_danaindonesiana.jumlah_dana_pendamping', 'pengajuan_danaindonesiana.bukti_dana_pendamping', 'pengajuan_danaindonesiana.ktp', 'pengajuan_danaindonesiana.surat_tugas', 'pengajuan_danaindonesiana.surat_undangan', 'pengajuan_danaindonesiana.pembiayaan', 'pengajuan_danaindonesiana.cv', 'pengajuan_danaindonesiana.portofolio', 'pengajuan_danaindonesiana.data_film', 'pengajuan_danaindonesiana.data_lampiran', 'pengajuan_danaindonesiana.data_pendukung', 'pengajuan_danaindonesiana.updated_at')
            ->get();
    }
}
