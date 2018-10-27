<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Model\WxUsers;

class TestController extends Controller
{
    public function index() {
        // var_dump(getenv());
        $user = WxUsers::where('wx_openid', '123')->first();
        var_dump($user);

    }
}
