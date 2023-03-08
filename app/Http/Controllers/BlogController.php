<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;
use File;
use Validator;
use App\Models\Blog;

class BlogController extends Controller
{
    public function index ()
    {
        $data = Blog::with('user')->orderBy('updated_at', 'desc')->select('id', 'user_id', 'tanggal', 'judul', 'kategori', 'slug', 'status', 'featured', 'updated_at')->limit(5000)->get();

    	return response()->json([
    		'data' => $data
    	]);
    }

    public function show ($slug)
    {
    	$data = Blog::with('user')->where('slug', $slug)->first();

        return response()->json([
    		'data' => $data
    	]);	
    }

    // arsip blog berdasarkan kategori
    public function arsip ($kategori)
    {
    	$data = DB::table('blog')
                ->leftJoin('users', 'blog.user_id', '=', 'users.id')
                ->orderBy('blog.updated_at', 'desc')
                ->where('kategori', $kategori)
                ->whereNull('blog.deleted_at')
                ->where('blog.status', 'terbit')
                ->select('users.name', 'blog.id', 'blog.tanggal', 'blog.judul', 'blog.slug', 'blog.meta', 'blog.status', 'blog.featured')
                ->limit(5000)->get();

    	return response()->json([
    		'data' => $data
    	]);
    }

    // buat atau update blog
    public function store (Request $request)
    {
    	$this->validate($request, [
    		'tanggal' => 'required',
            'judul' => 'required|string|max:250',
            'kategori' => 'required',
            'meta' => 'required|string|max:250',
            // 'featured' => 'required',
            'konten' => 'required|string',
            'status' => 'required'
        ], [
            'tanggal.required' => 'Tanggal harus diisi',
            'judul.required' => 'Judul harus diisi',
            'judul.max' => 'Judul maksimal 250 karakter',
            'kategori.required' => 'kategori harus dipilih',
            'meta.required' => 'Meta harus diisi',
            'meta.max' => 'Meta maksimal 250 karakter',
            'featured.required' => 'Foto/gambar sampul harus diunggah',
            'konten.required' => 'Konten harus diisi',
            'status.required' => 'Status harus dipilih'
        ]);

        $slug = $request->slug ?? Str::slug($request->judul, '-');
        $user_id = $request->user_id ? $request->user_id : auth()->user()->id;

        $blog = Blog::updateOrCreate(
          [
            'id' => $request->id
          ],
          [
            'user_id' => $user_id,
            'tanggal' => $request->tanggal,
            'judul' => $request->judul,
            'slug' => $slug,
            'kategori' => $request->kategori,
            'meta' => $request->meta,
            'kata_kunci' => $request->kata_kunci,
            'featured' => $request->featured,
            'konten' => $request->konten,
            'status' => $request->status
          ]);

        if ($blog) {
        	return response()->json([
        		'info' => 'Blog '.$request->judul.' telah '.$request->status
        	]);
        }
    }

    public function destroy ($id)
    {
        $data = Blog::find($id);
        if ($data->delete()) {
            return response()->json([
            	'info' => 'Data telah dihapus'
            ]);
        }
    }
}
