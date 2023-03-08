<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePemupukanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pemupukan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('jenis_pupuk_id');
            $table->date('tanggal');
            $table->string('estate', 20)->nullable();
            $table->string('divisi', 20)->nullable();
            $table->string('blok', 20)->nullable();
            $table->string('pokok', 20)->nullable();
            $table->string('tahun', 20)->nullable();
            $table->string('luas', 20)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('pemupukan', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('jenis_pupuk_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('jenis_pupuk_id')->references('id')->on('jenis_pupuk')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pemupukan');
    }
}
