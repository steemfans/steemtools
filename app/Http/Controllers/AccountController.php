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
        
        // 替换成国内源
        $auth_url = str_replace('steemconnect.com', 'steemconnect.wherein.mobi', $auth_url);

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

        $wxuser = WxUsers::where('wx_openid', $wx_openid)
                    ->where('username', $username)
                    ->first();

        if ($wxuser) {
            $sc2_id = env('STEEM_SC2_ID', 'steemtools.app');
            $wxuser->delete();
            return response()->view(
                'account/unbind',
                [
                    'wx_userinfo' => $wx_userinfo,
                    'username' => $wxuser->username,
                    'unbind_url' => 'https://steemconnect.wherein.mobi/revoke/@'.$sc2_id,
                ],
                200
            );
        } else {
            return redirect('/account/selector')
                    ->with('status0', '用户不存在');
        }
    }

    public function config(Request $request, $username) {
        $wx_userinfo = session('wechat.oauth_user.default');
        $wx_openid = $wx_userinfo->id;

        $wxuser = WxUsers::where('wx_openid', $wx_openid)
                    ->where('username', $username)
                    ->first();

        if ($wxuser) {
            // save config
            if ($request->isMethod('post')) {
                $settings = $request->input('settings');
                if ($settings) {
                    $wxuser->saveSettings($settings);
                }
                return redirect()->route('account_config', ['username' => $username])
                        ->with('status1', '配置已保存');
            }
            // view config
            $settings = json_decode($wxuser->settings, true);
            return response()->view(
                'account/config',
                [
                    'settings' => $settings,
                    'wx_userinfo' => $wx_userinfo,
                    'username' => $wxuser->username,
                ],
                200
            );
        } else {
            return redirect('/account/selector')
                    ->with('status0', '用户不存在');
        }

    }
}
