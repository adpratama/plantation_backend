<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use File;
use Validator;
use App\Models\JenisPupuk;

class JenisPupukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = JenisPupuk::orderBy('id', 'asc')->get();

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
            'nama' => 'required'
        ], [
            'nama.required' => 'Nama jenis pupuk harus diisi!',
        ]);

        $jenis_pupuk = JenisPupuk::updateOrCreate([
            'id' => $request->id
        ],
        [
            'nama' => $request->nama,
            'status' => $request->status,
        ]);

        if ($jenis_pupuk) {
            return response()->json([
                'info' => 'Data jenis pupuk telah ditambahkan.',
                'data' => $jenis_pupuk
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

        $data = JenisPupuk::find($id);

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
        $data = JenisPupuk::find($id);

        if($data->delete()) {
            return response()->json([
                'info' => 'Data jenis pupuk telah dihapus.'
            ]);
        }
    }
}
