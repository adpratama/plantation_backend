<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;
use File;
use Validator;
use App\Models\Pengaturan;

class PengaturanController extends Controller
{
    // list pengaturan untuk admin dan publik
    public function index ()
    {
    	$data = Pengaturan::orderBy('updated_at', 'desc')->get();
    	return response()->json([
    		'data' => $data
    	]);
    }

    public function slider()
    {
        $data = Pengaturan::where('nama', 'like', '%' . 'slider' . '%' )->orderBy('updated_at', 'desc')->first();
        return response()->json([
            'data' => $data
        ]);
    }

    public function show ($nama)
    {
    	$data = Pengaturan::where('nama', $nama)->first();
    	return response()->json([
    		'data' => $data
    	]);	
    }

    public function store (Request $request)
    {
    	$this->validate($request, [
            'nama' => 'required',
            'output' => 'required',
            'nilai' => 'required',
            'status' => 'required'
        ], [
            'nama.required' => 'Nama pengaturan harus diisi',
            'output.required' => 'Output pengaturan harus diunggah',
            'nilai.required' => 'Nilai pengaturan harus diisi',
            'status.required' => 'Status harus dipilih'
        ]);

        // $user_id = $request->user_id ? $request->user_id : auth()->user()->id;

        $pengaturan = Pengaturan::updateOrCreate(
          [
            'id' => $request->id
          ],
          [
            // 'user_id' => $user_id,
            'nama' => $request->nama,
            'output' => $request->output,
            'nilai' => $request->nilai,
            'status' => $request->status
          ]);

        if ($pengaturan) {
        	return response()->json([
        		'info' => 'Pengaturan '.$request->nama.' telah '.$request->status
        	]);
        }
    }

    public function store_slider(Request $request)
    {
        $nilai = $request->nilai;
        $slide = implode (",", $nilai);

        $slider = Pengaturan::updateOrCreate(
          [
            'id' => $request->id
          ],
          [
            'nama' => $request->nama,
            'output' => $request->output,
            'nilai' => $slide,
            'status' => $request->status
          ]);
      
        
        if ($slider) {
            return response()->json([
                'info' => 'Pengaturan '.$request->nama.' telah '.$request->status
            ]);
        } 
    }

    public function destroy ($id)
    {
        $data = Pengaturan::find($id);
        if ($data->delete()) {
            return response()->json([
            	'info' => 'Data telah dihapus'
            ]);
        }
    }
}
