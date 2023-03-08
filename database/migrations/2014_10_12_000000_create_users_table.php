<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('email', 100)->unique();
            $table->string('hp', 15)->nullable();
            $table->longText('password');
            $table->string('role', 25)->default('user');
            $table->longText('foto')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        // Schema::table('users', function (Blueprint $table) {
        //     $table->foreign('program_id')->references('id')->on('program')->onUpdate('cascade')->onDelete('cascade');
        //     $table->foreign('periode_id')->references('id')->on('periode')->onUpdate('cascade')->onDelete('cascade');
        // });

        DB::table('users')->insert(
            [
                'id' => 1,
                'name' => 'Superadmin App',
                'email' => 'ahmadbagwi.id@gmail.com',
                'hp' => '081101010101',
                'password' => Hash::make('qwerty123'),
                'role' => 'superadmin',
                'foto' => 'foto_superadmin.jpg'
            ]
        );

        DB::table('users')->insert(
            [
                'id' => 2,
                'name' => 'Admin App',
                'email' => 'admin@gmail.com',
                'hp' => '081100110011',
                'password' => Hash::make('qwerty123'),
                'role' => 'admin',
                'foto' => 'foto_admin.jpg'
            ]
        );

        DB::table('users')->insert(
            [
                'id' => 3,
                'name' => 'Nismara',
                'email' => 'nismara@gmail.com',
                'hp' => '081200110011',
                'password' => Hash::make('qwerty123'),
                'role' => 'user',
                'foto' => 'foto_user.jpg'
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
