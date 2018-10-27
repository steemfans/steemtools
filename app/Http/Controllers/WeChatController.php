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
        return "回复数字进行选择：\n1. 绑定 Steem 账号\n2. 设置需要提醒的内容\n".$this->ad();
    }


    private function subscribeEvent($msg, $userinfo) {
        $openid = $msg['FromUserName'];
        $this->checkAndInsertUser($openid, $userinfo);
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
        if (stristr($msg['Content'], 'bind')) {
            // 绑定用户
            $tmp = explode('bind', trim($msg['Content']));
            if ($tmp[1]) {
                $username = htmlspecialchars(strip_tags(substr($tmp[1], 0, 50)));
                if ($username) {
                    $tmp_user = WxUsers::where('wx_openid', $openid)->first();
                    $tmp_user->username = $username;
                    $tmp_user->save();
                    return "绑定 {$username} 成功，你可以继续去设置需要提醒那些类型的消息。".$this->ad();
                } else {
                    return '请输入正确的Steem用户名';
                }
            } else {
                return '请输入你的Steem用户名';
            }
        } else if (stristr($msg['Content'], 'help')) {
            // 显示帮助信息
            return $this->helpMsg();
        } else {
            switch ($msg['Content']) {
                case '1':
                    $user = $this->checkAndInsertUser($openid, $userinfo);
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
                    return '这是2菜单';
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
