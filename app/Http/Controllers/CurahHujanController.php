<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use File;
use Validator;
use App\Models\CurahHujan;

class CurahHujanController extends Controller
{
    public function index() {

        $data = DB::table('curah_hujan')
                    ->join('users', 'users.id', '=', 'curah_hujan.user_id')
                    ->select('curah_hujan.id', 'name', 'tanggal', 'estate', 'divisi', 'blok', 'luas')
                    ->whereNull('curah_hujan.deleted_at')
                    ->get();

        return response()->json([
            'data' => $data
        ]);

    }

    public function store(Request $request) {

        $this->validate($request, [
            'tanggal' => 'required',
            // 'estate' => 'required',
            // 'divisi' => 'required',
            // 'blok' => 'required',
            'luas' => 'required',
            // estate dan divisi diisi apa?
        ], [
            'tanggal.required' => 'Tanggal harus diisi!',
            'blok.required' => 'Blok harus diisi!',
            'divisi.required' => 'Divisi harus diisi!',
            'estate.required' => 'Estate harus diisi!',
            'luas.required' => 'Janjang harus diisi!'
        ]);

        $user_id = $request->user_id ? $request->user_id : auth()->user()->id;

        $curah_hujan = CurahHujan::updateOrCreate([
            'id' => $request->id
        ], [
            'user_id' => $request->user_id,
            'tanggal' => $request->tanggal,
            'estate' => $request->estate,
            'divisi' => $request->divisi,
            'blok' => $request->blok,
            'luas' => $request->luas,
        ]);

        if ($curah_hujan) {
            return response()->json([
                'info' => 'Data curah hujan Blok '.$request->blok.' telah ditambahkan.',
                'request' => var_dump($request->all()),
                'data' => var_dump($curah_hujan),
            ], 200);
        }
    }

    public function show($id) {

        $data = CurahHujan::find($id);

        return response()->json([
            'data' => $data
        ]);

    }

    public function destroy($id) {

        $data = CurahHujan::find($id);

        if ($data->delete()) {
            return response()->json([
                'info' => 'Data curah hujan telah dihapus.'
            ]);
        }

    }
}
