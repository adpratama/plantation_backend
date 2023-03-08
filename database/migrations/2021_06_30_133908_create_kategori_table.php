<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Kategori;

class CreateKategoriTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kategori', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('slug')->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        $data = array(
            [
                'id' => 1,
                'nama' => 'Berita',
                'slug' => 'berita'
            ],
            [
                'id' => 2,
                'nama' => 'SSD',
                'slug' => 'ssd'
            ],
            [
                'id' => 3,
                'nama' => 'Informasi',
                'slug' => 'informasi'
            ],
            [
                'id' => 4,
                'nama' => 'Komite Seleksi',
                'slug' => 'komite-seleksi'
            ]
        );

        foreach ($data as $data_insert){
            $kategori = new Kategori();
            $kategori->nama = $data_insert['nama'];
            $kategori->slug = $data_insert['slug'];
            $kategori->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kategori');
    }
}
