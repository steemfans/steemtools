<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;

use App\Model\WxUsers;

class WeChatController extends Controller
{
    /**
     * 处理微信的请求消息
     *
     * @return string
     */
    public function serve()
    {
        Log::info('request arrived.'); # 注意：Log 为 Laravel 组件，所以它记的日志去 Laravel 日志看，而不是 EasyWeChat 日志

        $app = app('wechat.official_account');
        $user = $app->user;
        $app->server->push(function($message) use ($user) {
            if (!isset($message['FromUserName'])) return;
            $userinfo = $user->get($message['FromUserName']);
            switch ($message['MsgType']) {
                case 'event':
                    // return '收到事件消息';
                    if ($message['Event'] == 'subscribe') {
                        $this->subscribeEvent($message, $userinfo);
                    }
                    return $this->helpMsg();
                    break;
                case 'text':
                    return $this->handleText($message, $userinfo);
                    break;
                case 'image':
                    // return '收到图片消息';
                    return $this->helpMsg();
                    break;
                case 'voice':
                    // return '收到语音消息';
                    return $this->helpMsg();
                    break;
                case 'video':
                    // return '收到视频消息';
                    return $this->helpMsg();
                    break;
                case 'location':
                    // return '收到坐标消息';
                    return $this->helpMsg();
                    break;
                case 'link':
                    // return '收到链接消息';
                    return $this->helpMsg();
                    break;
                case 'file':
                    // return '收到文件消息';
                    return $this->helpMsg();
                // ... 其它消息
                default:
                    // return '收到其它消息';
                    return $this->helpMsg();
                    break;
            }
        });

        return $app->server->serve();
    }

    public function menu() {
        $app = app('wechat.official_account');
        $buttons = [
            [
                "name"       => "我的",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "开始",
                        "url"  => "https://steem.to0l.cn/account/selector",
                    ],
                ],
            ],
            [
                "name"       => "小工具",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "SteemYY",
                        "url"  => "https://steem.to0l.cn/page/jump/steemyy",
                    ],
                    [
                        "type" => "view",
                        "name" => "见证人",
                        "url"  => "https://steem.to0l.cn/page/jump/witness",
                    ],
                    [
                        "type" => "view",
                        "name" => "SteemGG",
                        "url"  => "https://steem.to0l.cn/page/jump/steemgg",
                    ],
                ],
            ],
        ];
        return $app->menu->create($buttons);
    }

    public function testmenu() {
        $app = app('wechat.official_account');
        $buttons = [
            [
                "name"       => "我的",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "开始",
                        "url"  => "https://test.to0l.cn/account/selector",
                    ],
                ],
            ],
            [
                "name"       => "小工具",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "SteemYY",
                        "url"  => "https://test.to0l.cn/page/jump/steemyy",
                    ],
                    [
                        "type" => "view",
                        "name" => "见证人",
                        "url"  => "https://test.to0l.cn/page/jump/witness",
                    ],
                    [
                        "type" => "view",
                        "name" => "SteemGG",
                        "url"  => "https://test.to0l.cn/page/jump/steemgg",
                    ],
                ],
            ],
        ];
        return $app->menu->create($buttons);
    }

    private function helpMsg() {
        // return "回复数字进行选择：\n1. 绑定 Steem 账号\n2. 设置需要提醒的内容\n".$this->ad();
        return "欢迎关注 SteemTools!\n要开始使用 SteemTools 请点击菜单栏的“我的”=>“开始”进行配置";
    }


    private function subscribeEvent($msg, $userinfo) {
        $openid = $msg['FromUserName'];
        // $this->checkAndInsertUser($openid, $userinfo);
        return;
    }

    private function checkAndInsertUser($openid, $userinfo) {
        $user = WxUsers::where('wx_openid', $openid)->first();
        if (!$user) {
            $wxuser = new WxUsers;
            $wxuser->wx_openid = $openid;
            $wxuser->userinfo = json_encode($userinfo);
            $wxuser->save();
            return $wxuser;
        } else {
            return $user;
        }
    }

    private function handleText($msg, $userinfo) {
        $openid = $msg['FromUserName'];
        $user = $this->checkAndInsertUser($openid, $userinfo);
        $settings = $user->getSettingsIcon();
        if (stristr($msg['Content'], 'help')) {
            // 显示帮助信息
            return $this->helpMsg();
        } else {
            switch ($msg['Content']) {
                case '1':
                    // 进入绑定信息菜单
                    $tmp_share_msg = "发送 “bind你的Steem用户名” 完成新的绑定操作。\n\n例如Steem用户名为\nety001，那么就发送 bindety001 即可。";
                    if ($user['username']) {
                        return "目前绑定的用户名为:\n{$user['username']}\n\n".$tmp_share_msg;
                    } else {
                        return $tmp_share_msg;
                    }
                    break;
                case '2':
                    // 进入提醒设置
                    $menu = "输入下面的序号进行配置:\n";
                    $menu .= "21. 全选\n";
                    $menu .= "22. 全不选\n";
                    $menu .= "23. 回复提醒 {$settings['replies']['icon']}\n";
                    $menu .= "24. 收款提醒 {$settings['transfer']['icon']}\n";
                    $menu .= "25. 代理SP提醒 {$settings['delegate_vesting_shares']['icon']}\n";
                    $menu .= "26. 见证人得票提醒 {$settings['account_witness_vote']['icon']}\n";
                    return $menu.$this->ad();
                    break;
                case '21':
                    $user->settings = json_encode([
                        'replies' => 1,
                        'transfer'=>1,
                        'delegate_vesting_shares'=>1,
                        'account_witness_vote'=>1,
                    ]);
                    $user->save();
                    return '提醒已全部打开'.$this->ad();
                    break;
                case '22':
                    $user->settings = json_encode([
                        'replies' => 0,
                        'transfer'=> 0,
                        'delegate_vesting_shares'=> 0,
                        'account_witness_vote'=>0,
                    ]);
                    $user->save();
                    return '提醒已全部关闭'.$this->ad();
                    break;
                case '23':
                    $tmp_settings = $user->settings ?
                        json_decode($user->settings, true) : [];
                    if ($settings['replies']['r'] == 1) {
                        $tmp_settings['replies'] = 0;
                        $user->settings = json_encode($tmp_settings);
                        $user->save();
                        return '回复提醒已关闭'.$this->ad();
                    } else {
                        $tmp_settings['replies'] = 1;
                        $user->settings = json_encode($tmp_settings);
                        $user->save();
                        return '回复提醒已打开'.$this->ad();
                    }
                    break;
                case '24':
                    $tmp_settings = $user->settings ?
                        json_decode($user->settings, true) : [];
                    if ($settings['transfer']['r'] == 1) {
                        $tmp_settings['transfer'] = 0;
                        $user->settings = json_encode($tmp_settings);
                        $user->save();
                        return '收款提醒已关闭'.$this->ad();
                    } else {
                        $tmp_settings['transfer'] = 1;
                        $user->settings = json_encode($tmp_settings);
                        $user->save();
                        return '收款提醒已打开'.$this->ad();
                    }
                    break;
                case '25':
                    $tmp_settings = $user->settings ?
                        json_decode($user->settings, true) : [];
                    if ($settings['delegate_vesting_shares']['r'] == 1) {
                        $tmp_settings['delegate_vesting_shares'] = 0;
                        $user->settings = json_encode($tmp_settings);
                        $user->save();
                        return '代理SP提醒已关闭'.$this->ad();
                    } else {
                        $tmp_settings['delegate_vesting_shares'] = 1;
                        $user->settings = json_encode($tmp_settings);
                        $user->save();
                        return '代理SP提醒已打开'.$this->ad();
                    }
                    break;
                case '26':
                    $tmp_settings = $user->settings ?
                        json_decode($user->settings, true) : [];
                    if ($settings['account_witness_vote']['r'] == 1) {
                        $tmp_settings['account_witness_vote'] = 0;
                        $user->settings = json_encode($tmp_settings);
                        $user->save();
                        return '见证人得票提醒已关闭'.$this->ad();
                    } else {
                        $tmp_settings['account_witness_vote'] = 1;
                        $user->settings = json_encode($tmp_settings);
                        $user->save();
                        return '见证人得票提醒已打开'.$this->ad();
                    }
                    break;
                default:
                    return $this->helpMsg();
            }
        }
    }

    private function ad() {
        return '';
    }
}
