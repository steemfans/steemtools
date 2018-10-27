<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWxUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wx_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 50)->nullable();
            $table->string('wx_openid', 32);
            $table->string('email', 32)->nullable();
            $table->string('sc_access_token', 256)->nullable();
            $table->integer('sc_expires_in')->nullable();
            $table->json('settings')->nullable();
            $table->json('userinfo')->nullable();
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
        Schema::dropIfExists('wx_users');
    }
}
