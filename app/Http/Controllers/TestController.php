<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Model\WxUsers;

class TestController extends Controller
{
    public function index() {
        // var_dump(getenv());
        $user = WxUsers::where('username', 'ety001')->first();
        var_dump($user->getSettingsIcon());

    }
}
