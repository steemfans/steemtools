<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\WxUsers;
use Log;

class SteemController extends Controller
{
    public function callback(Request $request) {
        $wx_userinfo = session('wechat.oauth_user.default');
        $wx_openid = $wx_userinfo->id;
        $sc_code = $request->input('code');
        // var_dump($sc_code);die();
        try {
            $sc2 = app('sc2.client');
            $token = $sc2->auth()->parseReturn($sc_code)->toArray();
            $wxuser = WxUsers::where('wx_openid', $wx_openid)
                        ->where('username', $token['username'])->first();
            if (!$wxuser) {
                $waiting_to_insert = [
                    'username' => $token['username'],
                    'wx_openid' => $wx_openid,
                    'sc_code' => $sc_code,
                    'sc_access_token' => $token['access_token'],
                    'sc_refresh_token' => $token['refresh_token'],
                    'sc_expires_in' => $token['expires'],
                    'settings' => json_encode([]),
                    'userinfo' => json_encode($wx_userinfo),
                ];
                $wxuser = WxUsers::create($waiting_to_insert);
                return redirect('/account/selector')
                    ->with('status1', '绑定成功');
            } else {
                return redirect('/account/selector')
                    ->with('status0', '已经绑定过了');
            }
        } catch (\Exception $e) {
            Log::error('bind_error', [$e->getMessage(), $request->input()]);
            return redirect('/account/selector')
                ->with('status0', '绑定失败, '.$e->getMessage());
        }
    }

}
