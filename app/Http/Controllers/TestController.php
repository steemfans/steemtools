<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Model\WxUsers;
use App\Model\Settings;

class TestController extends Controller
{
    public function index() {
        // var_dump(getenv());
        // $user = WxUsers::where('username', 'ety001')->first();
        // var_dump($user->getSettingsIcon());
        // echo steem_per_mvests();

        // $steem_per_mvest = Settings::steem_per_mvests();
        // $vesting_shares = '201715.576726';
        // echo number_format(vests_to_sp($vesting_shares, $steem_per_mvest), 3);

        $post = get_content_by_account_and_title('ety001', 'pubg-mobile-03110');
        var_dump($post);

    }
}
