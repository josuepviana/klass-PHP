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
            $table->increments('id');
            $table->string('email');
            $table->string('password');
            $table->string('avatar')->default('user.jpg');
            $table->string('banner');
            $table->string('nome');
            $table->string('sobrenome');
            $table->date('data_nascimento');
            $table->string('username')->unique();
            $table->string('sobre')->nullable();
            $table->string('trabalho')->nullable();
            $table->string('localizacao')->nullable();
            $table->string('faculdade');
            $table->string('curso');
            $table->string('relacionamento')->nullable();
            $table->string('remember_token')->nullable();
            $table->timestamps();
        });
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
