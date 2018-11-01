<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\WxUsers;

class PageController extends Controller
{
    public function jump($website) {
        $userinfo = session('wechat.oauth_user.default');
        $userid = $userinfo->id;
        $user = WxUsers::where('wx_openid')->first();
        if ($user) {
            $steem_username = $user->username;
        } else {
            $steem_username = '';
        }
        switch($website) {
            case 'steemyy':
                $url = 'https://steemyy.com/steemit-tools/';
                $text = '@justyy';
                $sitename = 'SteemYY';
                break;
            case 'witness':
                $url = 'https://www.eztk.net/witnesses.php?id='.$steem_username;
                $text = '@oflyhigh';
                $sitename = '见证人列表';
                break;
            case 'steemgg':
                $url = 'https://steemgg.com/';
                $text = '@bobdos @bizheng @bonjovis @kanny10 @stabilowl';
                $sitename = 'SteemGG';
                break;
            default:
                $url = '';
                $text = '暂时没有你要找的网站';
                $sitename = '';
        }
        return response()->view(
            'page/jump',
            [
                'url' => $url,
                'website' => $website,
                'text' => $text,
                'sitename' => $sitename,
            ],
            200
        );
    }

    public function sellvote() {
        $data = [];
        return response()->view(
            'page/sellvote',
            $data,
            200
        );
    }

    public function tools() {
        $data = [];
        return response()->view(
            'page/tools',
            $data,
            200
        );
    }

}
