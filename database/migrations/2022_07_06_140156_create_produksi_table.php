<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProduksiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produksi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->date('tanggal');
            $table->string('estate', 20)->nullable();
            $table->string('divisi', 20)->nullable();
            $table->string('blok', 20)->nullable();
            $table->string('pokok', 20)->nullable();
            $table->string('tahun', 20)->nullable();
            $table->string('berat', 20)->nullable();
            $table->string('jenjang', 20)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('produksi', function (Blueprint $table) {
            $table->index('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produksi');
    }
}
