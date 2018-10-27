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

    private function helpMsg() {
        return "回复数字进行选择：\n1. 绑定 Steem 账号\n2. 设置需要提醒的内容\n";
    }


    private function subscribeEvent($msg, $userinfo) {
        $openid = $msg['FromUserName'];
        $user = WxUsers::where('wx_openid', $openid)->first();
        if (!$user) {
            $wxuser = new WxUsers;
            $wxuser->wx_openid = $openid;
            $wxuser->userinfo = json_encode($userinfo);
            $wxuser->save();
        }
        return;
    }

    private function handleText($msg, $userinfo) {
        if (stristr($msg['Content'], 'bind')) {
            // 绑定用户
        } else if (stristr($msg['Content'], 'help')) {
            // 显示帮助信息
            return $this->helpMsg();
        } else {
            switch ($msg['Content']) {
                case '1':
                    // 进入绑定信息菜单
                    return '这是1菜单';
                    break;
                case '2':
                    // 进入提醒设置
                    return '这是2菜单';
                    break;
                default:
                    return $this->helpMsg();
            }
        }
    }
}
