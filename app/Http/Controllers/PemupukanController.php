<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use File;
use Validator;
use App\Models\Pemupukan;
use App\Models\User;
use App\Models\JenisPupuk;

class PemupukanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('pemupukan')
                ->join('users', 'users.id', '=', 'pemupukan.user_id')
                ->join('jenis_pupuk', 'jenis_pupuk.id', '=', 'pemupukan.jenis_pupuk_id')
                ->select('pemupukan.id', 'name', 'tanggal', 'estate', 'divisi', 'blok', 'pokok', 'nama', 'tahun', 'luas')
                ->whereNull('pemupukan.deleted_at')
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
            'jenis_pupuk' => 'required',
            'tahun' => 'required',
            'luas' => 'required'
        ], [
            'tanggal.required' => 'Tanggal harus diisi!',
            'estate.required' => 'Estate harus diisi!',
            'divisi.required' => 'Divisi harus diisi!',
            'blok.required' => 'Blok harus diisi!',
            'pokok.required' => 'Janjang harus diisi!',
            'jenis_pupuk.required' => 'Jenis pekerjaan harus diisi!',
            'tahun.required' => 'Tahun harus diisi!',
            'luas.required' => 'Luas harus diisi!'
        ]);

        $user_id = $request->user_id ? $request->user_id : auth()->user()->id;

        $pemupukan = Pemupukan::updateOrCreate([
            'id' => $request->id
        ],
        [
            'user_id' => $user_id,
            'tanggal' => $request->tanggal,
            'estate' => $request->estate,
            'divisi' => $request->divisi,
            'blok' => $request->blok,
            'pokok' => $request->pokok,
            'jenis_pupuk_id' => $request->jenis_pupuk,
            'tahun' => $request->tahun,
            'luas' => $request->luas
        ]
        );

        if ($pemupukan) {
            return response()->json([
                'info' => 'Data pemupukan berhasil ditambahkan.',
                'data' => $pemupukan
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
        $data = DB::table('pemupukan')
                ->join('users', 'users.id', '=', 'pemupukan.user_id')
                ->join('jenis_pupuk', 'jenis_pupuk.id', '=', 'pemupukan.jenis_pupuk_id')
                ->select('pemupukan.id', 'name', 'tanggal', 'estate', 'divisi', 'blok', 'pokok', 'nama', 'tahun', 'luas')
                ->where('pemupukan.id', $id)
                ->whereNull('pemupukan.deleted_at')
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
        $data = Pemupukan::find($id);

        if($data->delete()) {
            return response()->json([
                'info' => 'Data telah dihapus.'
            ]);
        }
    }
}
