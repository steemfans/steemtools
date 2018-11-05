<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesJumpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages_jump', function (Blueprint $table) {
            $table->increments('id');
            $table->string('keyword', 500);
            $table->string('url', 500);
            $table->string('sitename', 500);
            $table->string('descp', 500);
            $table->smallInteger('status')->default(0);
            $table->integer('order_index')->default(1);
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
        Schema::dropIfExists('pages_jump');
    }
}
