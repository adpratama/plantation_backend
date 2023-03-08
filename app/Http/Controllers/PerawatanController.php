<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use File;
use Validator;
use App\Models\Perawatan;
use App\Models\User;
use App\Models\JenisPekerjaan;

class PerawatanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('perawatan')
                ->join('users', 'users.id', '=', 'perawatan.user_id')
                ->join('jenis_pekerjaan', 'jenis_pekerjaan.id', '=', 'perawatan.jenis_pekerjaan_id')
                ->select('perawatan.id', 'name', 'tanggal', 'estate', 'divisi', 'blok', 'pokok', 'nama', 'tahun', 'luas', 'rotasi')
                ->whereNull('perawatan.deleted_at')
                ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'tanggal' => 'required',
            'estate' => 'required',
            'divisi' => 'required',
            'blok' => 'required',
            'pokok' => 'required',
            'tahun' => 'required',
            'luas' => 'required',
            'rotasi' => 'required',
            'jenis_pekerjaan' => 'required'
        ], [
            'tanggal.required' => 'Tanggal harus diisi!',
            'estate.required' => 'Estate harus diisi!',
            'divisi.required' => 'Divisi harus diisi!',
            'blok.required' => 'Blok harus diisi!',
            'pokok.required' => 'Janjang harus diisi!',
            'tahun.required' => 'Tahun harus diisi!',
            'luas.required' => 'Luas harus diisi!',
            'rotasi.required' => 'Rotasi harus diisi!',
            'jenis_pekerjaan.required' => 'Jenis pekerjaan harus diisi!'
        ]);

        $user_id = $request->user_id ? $request->user_id : auth()->user()->id;

        $perawatan = Perawatan::updateOrCreate(        [
            'id' => $request->id
        ],
        [
            'user_id' => $user_id,
            'tanggal' => $request->tanggal,
            'estate' => $request->estate,
            'divisi' => $request->divisi,
            'blok' => $request->blok,
            'pokok' => $request->pokok,
            'jenis_pekerjaan_id' => $request->jenis_pekerjaan,
            'tahun' => $request->tahun,
            'luas' => $request->luas,
            'rotasi' => $request->rotasi,
        ]
        );

        if ($perawatan) {
            return response()->json([
                'info' => 'Data perawatan berhasil ditambahkan.',
                'data' => $perawatan
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = DB::table('perawatan')
                ->join('users', 'users.id', '=', 'perawatan.user_id')
                ->join('jenis_pekerjaan', 'jenis_pekerjaan.id', '=', 'perawatan.jenis_pekerjaan_id')
                ->select('perawatan.id', 'name', 'tanggal', 'estate', 'divisi', 'blok', 'pokok', 'nama', 'tahun', 'luas', 'rotasi')
                ->where('perawatan.id', $id)
                ->whereNull('perawatan.deleted_at')
                ->first();

        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Perawatan::find($id);

        if($data->delete()) {
            return response()->json([
                'info' => 'Data telah dihapus.'
            ]);
        }
    }
}
