<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\WxUsers;

class AccountController extends Controller
{
    public function selector() {
        $wx_userinfo = session('wechat.oauth_user.default');
        $wx_openid = $wx_userinfo->id;
        // var_dump($wx_openid);die();
        // var_dump($wx_userinfo);die();

        // 获取该 wx_openid 绑定的所有 Steem 账号
        $wxusers = WxUsers::where('wx_openid', $wx_openid)->get();
        // var_dump($wxusers);die();
        if ($wxusers->count()>0) {
            foreach($wxusers as $k => $tmp) {
                // 如果不存在 username 说明是第一版本的数据，清理掉
                // 如果不存在 sc_code 说明是第一版本的数据，清理掉
                if (!$tmp->username || !$tmp->sc_code) {
                    $tmp->delete();
                    unset($wxusers[$k]);
                }
            }
        }

        // 获取 steem 的授权地址
        $sc2 = app('sc2.client');
        $auth_url = $sc2->auth()->getAuthorizationUrl();

        return response()->view(
            'account/selector',
            [
                'wxusers' => $wxusers,
                'wx_userinfo' => $wx_userinfo,
                'auth_url' => $auth_url,
            ],
            200
        );
    }

    public function unbind($username) {
        $wx_userinfo = session('wechat.oauth_user.default');
        $wx_openid = $wx_userinfo->id;

    }

    public function config($username) {
        $wx_userinfo = session('wechat.oauth_user.default');
        $wx_openid = $wx_userinfo->id;

    }
}
