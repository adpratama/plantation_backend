<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use File;
use Validator;
use App\Models\Produksi;
use App\Models\User;

class ProduksiController extends Controller
{
    public function index(Request $request)
    {
        $data = DB::table('produksi')
                ->join('users', 'users.id', '=', 'produksi.user_id')
                ->select('produksi.id', 'name', 'tanggal', 'estate', 'divisi', 'blok', 'pokok', 'tahun', 'berat', 'jenjang')
                ->whereNull('produksi.deleted_at')
                ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function store (Request $request)
    {
        $this->validate($request, [
            'tanggal' => 'required',
            'blok' => 'required',
            'tahun' => 'required',
            'berat' => 'required',
            'jenjang' => 'required',
            //'estate' => 'required',
            //'divisi' => 'required',
            //'pokok' => 'required',
            // estate, divisi, dan pokok diisi apa?
        ], [
            'tanggal.required' => 'Tanggal harus diisi!',
            'blok.required' => 'Blok harus diisi!',
            'tahun.required' => 'Tahun harus diisi!',
            'berat.required' => 'Berat harus diisi!',
            'jenjang.required' => 'Jenjang harus diisi!',
            'divisi.required' => 'Divisi harus diisi!',
            'estate.required' => 'Estate harus diisi!',
            'pokok.required' => 'jenjang harus diisi!'
        ]);

        $user_id = $request->user_id ? $request->user_id : $request->user()->id;

        // dump($request->all());
        $produksi = Produksi::updateOrCreate([
            'id' => $request->id
        ], [
            'user_id' => $request->user_id,
            'estate' => $request->estate,
            'divisi' => $request->divisi,
            'tanggal' => $request->tanggal,
            'blok' => $request->blok,
            'tahun' => $request->tahun,
            'berat' => $request->berat,
            'jenjang' => $request->jenjang,
            'pokok' => $request->pokok
        ]);

        if($produksi) {
            return response()->json([
                'info' => 'Data produksi berhasil ditambahkan.',
                'data' => $produksi
            ], 200);
        }
    }

    public function show($id)
    {
        $data = DB::table('produksi')
                ->join('users', 'users.id', '=', 'produksi.user_id')
                ->select('produksi.id', 'name', 'tanggal', 'estate', 'divisi', 'blok', 'pokok', 'tahun', 'berat', 'jenjang')
                ->where('produksi.id', $id)
                ->whereNull('produksi.deleted_at')
                ->first();

        return response()->json([
            'data' => $data
        ]);
    }

    public function update(Request $request)
    {

    }

    public function destroy($id)
    {
        $data = Produksi::find($id);

        if($data->delete()) {
            return response()->json([
                'info' => 'Data berhasil dihapus.'
            ]);
        }
    }
}
