<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;
use File;
use Validator;
use App\Models\Blog;
use App\Models\Periode;
use App\Models\Pengaturan;
use App\Models\Program;
use App\Models\Profil;

class LandingController extends Controller
{
    // ambil data provinsi, kota dan kecamatan untuk pendaftaran
    public function provinsi ()
    {
        $data = DB::table('provinces')->select('id', 'name')->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function kota ($provinsi_id)
    {
        $data = DB::table('regencies')->where('province_id', $provinsi_id)->select('id', 'name')->orderBy('name', 'asc')->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function kecamatan ($kota_id)
    {
        $data = DB::table('districts')->where('regency_id', $kota_id)->select('id', 'name')->orderBy('name', 'asc')->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function kelurahan ($kecamatan_id)
    {
        $data = DB::table('villages')->where('district_id', $kecamatan_id)->select('id', 'name')->orderBy('name', 'asc')->get();

        return response()->json([
            'data' => $data
        ]);
    }

    // landing page (halaman induk)
    public function index ()
    {

        // $periode = Periode::where('status', 'tidak aktif')->select('id')->get();

        // foreach ($periode as $data) {
        //     $dokumentasi = DB::table('profil')->join('periode', 'profil.periode_id', '=', 'periode.id')->whereNull('profil.deleted_at')->where([['kategori', 'like', 'Dokumentasi' . '%'], ['periode.id', $data->id]])->limit(5)->orderBy('profil.updated_at', 'desc')->select('profil.id', 'periode.id as periode_id', 'periode.tahun', 'profil.nama_penerima', 'profil.nama_project', 'profil.foto_penerima', 'profil.kategori', 'profil.updated_at')->get()->toArray();
        // }

        $dokumentasi1 = DB::table('profil')->join('periode', 'profil.periode_id', '=', 'periode.id')->whereNull('profil.deleted_at')->where([['kategori', 'like', 'Dokumentasi' . '%'], ['periode.id', 1]])->limit(5)->orderBy('profil.updated_at', 'desc')->select('profil.id', 'periode.id as periode_id', 'periode.tahun', 'profil.nama_penerima', 'profil.slug', 'profil.nama_project', 'profil.foto_penerima', 'profil.kategori', 'profil.updated_at')->get()->toArray();
        $dokumentasi2 = DB::table('profil')->join('periode', 'profil.periode_id', '=', 'periode.id')->whereNull('profil.deleted_at')->where([['kategori', 'like', 'Dokumentasi' . '%'], ['periode.id', 2]])->limit(5)->orderBy('profil.updated_at', 'desc')->select('profil.id', 'periode.id as periode_id', 'periode.tahun', 'profil.nama_penerima', 'profil.slug', 'profil.nama_project', 'profil.foto_penerima', 'profil.kategori', 'profil.updated_at')->get()->toArray();
        $dokumentasi_gabungan = array_merge($dokumentasi1, $dokumentasi2);

        $penciptaan1 = DB::table('profil')->join('periode', 'profil.periode_id', '=', 'periode.id')->whereNull('profil.deleted_at')->where([['kategori', 'like', 'Penciptaan' . '%'], ['periode.id', 1]])->limit(5)->orderBy('profil.updated_at', 'desc')->select('profil.id', 'periode.id as periode_id', 'periode.tahun', 'profil.nama_penerima', 'profil.slug', 'profil.nama_project', 'profil.foto_penerima', 'profil.kategori', 'profil.updated_at')->get()->toArray();
        $penciptaan2 = DB::table('profil')->join('periode', 'profil.periode_id', '=', 'periode.id')->whereNull('profil.deleted_at')->where([['kategori', 'like', 'Penciptaan' . '%'], ['periode.id', 2]])->limit(5)->orderBy('profil.updated_at', 'desc')->select('profil.id', 'periode.id as periode_id', 'periode.tahun', 'profil.nama_penerima', 'profil.slug', 'profil.nama_project', 'profil.foto_penerima', 'profil.kategori', 'profil.updated_at')->get()->toArray();
        $penciptaan_gabungan = array_merge($penciptaan1, $penciptaan2);

        $pendayagunaan1 = DB::table('profil')->join('periode', 'profil.periode_id', '=', 'periode.id')->whereNull('profil.deleted_at')->where([['kategori', 'like', 'Pendayagunaan' . '%'], ['periode.id', 1]])->limit(5)->orderBy('profil.updated_at', 'desc')->select('profil.id', 'periode.id as periode_id', 'periode.tahun', 'profil.nama_penerima', 'profil.nama_project', 'profil.foto', 'profil.foto_penerima', 'profil.slug', 'profil.kategori', 'profil.updated_at')->get()->toArray();
        $pendayagunaan2 = DB::table('profil')->join('periode', 'profil.periode_id', '=', 'periode.id')->whereNull('profil.deleted_at')->where([['kategori', 'like', 'Pendayagunaan' . '%'], ['periode.id', 2]])->limit(5)->orderBy('profil.updated_at', 'desc')->select('profil.id', 'periode.id as periode_id', 'periode.tahun', 'profil.nama_penerima', 'profil.nama_project', 'profil.foto', 'profil.foto_penerima', 'profil.slug', 'profil.kategori', 'profil.updated_at')->get()->toArray();
        $pendayagunaan_gabungan = array_merge($pendayagunaan1, $pendayagunaan2);

        return response()->json([
            'maintenance' => Pengaturan::where('nama', 'maintenance')->where('status', 'aktif')->first(),
            'slider' => Pengaturan::where('nama', 'slider')->where('status', 'terbit')->first(),
            'penerima' => [
                'dokumentasi' => $dokumentasi_gabungan,
                'penciptaan' => $penciptaan_gabungan,
                'pendayagunaan' => $pendayagunaan_gabungan
            ],
            // 'program' => Program::where('status', 'aktif')->get(),
            'program' => Program::where('status', 'aktif')->select('id', 'nama', 'slug', 'status_pendaftaran')->orderBy('id', 'asc')->get(),
            'blog' => Blog::where('kategori', 'blog')->limit(3)->select('id', 'tanggal', 'judul', 'featured', 'slug', 'meta')->orderBy('created_at', 'desc')->get(),
            'profil' => Profil::whereNull('deleted_at')->select('id', 'periode_id', 'nama_penerima', 'nama_project', 'foto_penerima', 'kategori', 'updated_at')->limit(10)->get(),
            'periode' => Periode::select('id', 'tahun', 'status')->where('status', 'tidak aktif')->get(),
            'intro' => Blog::where('kategori', 'intro')->select('id', 'tanggal', 'judul', 'slug', 'meta', 'konten')->get(),
            'komite' => Blog::where('kategori', 'komite')->select('id', 'tanggal', 'judul', 'featured', 'kategori', 'slug', 'meta')->get(),
            'faq' => Blog::where('kategori', 'faq')->select('id', 'tanggal', 'judul', 'slug', 'meta', 'konten')->get(),
            'sambutan' => Pengaturan::where('nama', 'sambutan')->where('status', 'aktif')->first(),
            'popup_landing' => Pengaturan::where('nama', 'popup_landing')->where('status', 'aktif')->orderBy('updated_at', 'desc')->first()
        ]);
    }

    public function maintenance ()
    {
        $data = Pengaturan::where('nama', 'maintenance')->where('status', 'aktif')->first();

        return response()->json([
            'data' => $data
        ]);
    }
}
