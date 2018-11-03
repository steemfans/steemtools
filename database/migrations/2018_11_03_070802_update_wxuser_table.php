<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateWxuserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wx_users', function (Blueprint $table) {
            $table->string('sc_access_token', 500)->nullable()->change();
            $table->string('sc_refresh_token', 500)->nullable()->after('sc_access_token');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wx_users', function (Blueprint $table) {
            $table->dropColumn('sc_refresh_token');
            $table->string('sc_access_token', 256)->nullable()->change();
        });
    }
}
