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
                        "url"  => url('/account/selector'),
                    ],
                ],
            ],
            [
                "name"       => "应用&工具",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "Steem 指南",
                        "url"  => url('/page/jump/steemh'),
                    ],
                    [
                        "type" => "view",
                        "name" => "SteemYY",
                        "url"  => url('/page/jump/steemyy'),
                    ],
                    [
                        "type" => "view",
                        "name" => "见证人",
                        "url"  => url('/page/jump/witness'),
                    ],
                    [
                        "type" => "view",
                        "name" => "SteemGG",
                        "url"  => url('/page/jump/steemgg'),
                    ],
                    [
                        "type" => "view",
                        "name" => "更多",
                        "url"  => url('/page/more'),
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
                "name"       => "应用&工具",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "Steem 指南",
                        "url"  => "https://steem.to0l.cn/page/jump/steemh",
                    ],
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
                    [
                        "type" => "view",
                        "name" => "更多",
                        "url"  => "https://test.to0l.cn/page/more",
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
        } else if (stristr($msg['Content'], 'https://steemit.com')) {
            // 转换文章URL
            $preg = '/https:\/\/steemit.com\/(.+)\/@(.+)\/(.+)/i';
            $matches = [];
            preg_match($preg, $msg['Content'], $matches);
            if (count($matches) === 4) {
                return 'https://steem.to0l.cn/steempage/post/@'.$matches[2].'/'.$matches[3];
            }
            return '网址格式不对';
        } else {
            switch ($msg['Content']) {
                default:
                    return $this->helpMsg();
            }
        }
    }

    private function ad() {
        return '';
    }
}
