<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerawatanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perawatan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('jenis_pekerjaan_id');
            $table->date('tanggal');
            $table->string('estate', 20)->nullable();
            $table->string('divisi', 20)->nullable();
            $table->string('blok', 20)->nullable();
            $table->string('pokok', 20)->nullable();
            $table->string('tahun', 20)->nullable();
            $table->string('luas', 20)->nullable();
            $table->string('rotasi', 20)->nullable();
            $table->timestamps();
            $table->SoftDeletes();
        });

        Schema::table('perawatan', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('jenis_pekerjaan_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('jenis_pekerjaan_id')->references('id')->on('jenis_pekerjaan')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('perawatan');
    }
}
