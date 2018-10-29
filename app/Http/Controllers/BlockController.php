<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;

use App\Model\WxUsers;
use App\Model\Settings;

class BlockController extends Controller
{
    public function info(Request $request) {
        $data = json_decode($request->getContent(), true);
        // var_dump($data);die();
        $data_type = $data[0];
        $data = $data[1];
        // 回复提醒
        if ($data_type == 'comment') {
            $parent_author = isset($data['parent_author']) ? $data['parent_author'] : null;
            $parent_permlink = isset($data['parent_permlink']) ? $data['parent_permlink'] : null;
            $author = isset($data['author']) ? $data['author'] : null;
            $permlink = isset($data['permlink']) ? $data['permlink'] : null;
            $title = isset($data['title']) ? $data['title'] : null;
            $body = isset($data['body']) ? $data['body'] : null;
            $json_metadata = isset($data['json_metadata']) ? $data['json_metadata'] : null;
            if (trim($parent_author) === trim($author)) {
                $result = 'not_mention_yourself:'.$parent_author.':'.$author;
                $code = -2;
            } else {
                $user = WxUsers::where('username', $parent_author)->first();
                // var_dump($user);die();
                if ($user) {
                    $settings = json_decode($user->settings, true);
                    if ( isset($settings['replies']) && $settings['replies'] == 1) {
                        try {
                            // 引入微信SDK
                            $app = app('wechat.official_account');
                            $tmpl_id = getenv('WECHAT_TMPL_REPLY_ID');
                            $reply_url = 'https://steemit.com/'.$parent_permlink.'/@'.$author.'/'.$permlink;
                            // 发送微信模板消息
                            $app->template_message->send([
                                'touser' => $user['wx_openid'],
                                'template_id' => $tmpl_id,
                                'url' => $reply_url,
                                'data' => [
                                    'first' => $author.' 回复了你的文章',
                                    'keyword1' => date('Y-m-d H:i:s', time()),
                                    'keyword2' => $body,
                                    'remark' => '点击可以查看详情',
                                ],
                            ]);
                            $result = 'send replies success';
                            $code = 1;
                        } catch (Exception $e) {
                            $result = $e->getMessage();
                            $code = -3;
                            Log::error('send_replies_error:'. $e->getMessage());
                        }
                    } else {
                        $result = 'get user but not set replies on';
                        $code = 0;
                    }
                } else {
                    $result = $parent_author . ' is not in db.';
                    $code = -1;
                }
            }
            return response()->json([
                'result' => $result,
                'code' => $code,
            ]);
        }
        // 转账提醒
        if ($data_type == 'transfer') {
            $from = isset($data['from']) ? $data['from'] : null;
            $to = isset($data['to']) ? $data['to'] : null;
            $amount = isset($data['amount']) ? $data['amount'] : null;
            $memo = isset($data['memo']) ? $data['memo'] : null;

            $user = WxUsers::where('username', $to)->first();
            if ($user) {
                $settings = json_decode($user->settings, true);
                if ( isset($settings['transfer']) && $settings['transfer'] == 1) {
                    try {
                        // 引入微信SDK
                        $app = app('wechat.official_account');
                        $tmpl_id = getenv('WECHAT_TMPL_CHANGE_ID');

                        $transfer_url = 'https://steemit.com/@'.$to.'/transfers';
                        // 发送微信模板消息
                        $app->template_message->send([
                            'touser' => $user['wx_openid'],
                            'template_id' => $tmpl_id,
                            'url' => $transfer_url,
                            'data' => [
                                'first' => "{$to}，你收到了 {$from} 发给你的 {$amount}",
                                'keyword1' => date('Y-m-d H:i:s', time()),
                                'keyword2' => '收款',
                                'keyword3' => $amount,
                                'remark' => $memo
                                            ?
                                            "备注消息: {$memo}\n\n点击可以查看详情"
                                            :
                                            '点击可以查看详情',
                            ],
                        ]);
                        $result = 'transfer to' . $to. ' and sent wx msg';
                        $code = 1;
                    } catch (Exception $e) {
                        $result = $e->getMessage();
                        $code = -3;
                        Log::error('send_transfer_error:'. $e->getMessage());
                    }
                } else {
                    $result = 'transfer to' . $to.' but not set transfer on';
                    $code = 0;
                }
            } else {
                $result = $to. ' is not in db.';
                $code = -1;
            }
            return response()->json([
                'result' => $result,
                'code' => $code,
            ]);
        }
        // 代理提醒
        if ($data_type == 'delegate_vesting_shares') {
            $delegator = isset($data['delegator']) ? $data['delegator'] : null;
            $delegatee = isset($data['delegatee']) ? $data['delegatee'] : null;
            $vesting_shares = isset($data['vesting_shares']) ? $data['vesting_shares'] : null;

            $user = WxUsers::where('username', $delegatee)->first();
            if ($user) {
                $settings = json_decode($user->settings, true);
                if ( isset($settings['delegate_vesting_shares']) && $settings['delegate_vesting_shares'] == 1) {
                    try {
                        // 引入微信SDK
                        $app = app('wechat.official_account');
                        $tmpl_id = getenv('WECHAT_TMPL_CHANGE_ID');

                        $delegate_url = 'https://steemd.com/@'.$delegatee;
                        $steem_per_mvest = Settings::steem_per_mvests();
                        if ($steem_per_mvest) {
                            $sp = number_format(vests_to_sp($vesting_shares, $steem_per_mvest), 3).' SP';
                        } else {
                            $sp = false;
                        }
                        // 发送微信模板消息
                        $app->template_message->send([
                            'touser' => $user['wx_openid'],
                            'template_id' => $tmpl_id,
                            'url' => $delegate_url,
                            'data' => [
                                'first' => "{$delegatee}，你收到了 {$delegator} 的SP代理",
                                'keyword1' => date('Y-m-d H:i:s', time()),
                                'keyword2' => 'SP 代理',
                                'keyword3' => $sp ? $sp : $vesting_shares,
                                'remark' => '点击可以查看详情',
                            ],
                        ]);
                        $result = $delegator . ' delegate to' . $delegatee. ' and sent wx msg';
                        $code = 1;
                    } catch (Exception $e) {
                        $result = $e->getMessage();
                        $code = -3;
                        Log::error('send_delegate_error:'. $e->getMessage());
                    }
                } else {
                    $result = $delegator . ' delegate to' . $delegatee. ' but not set delegate on';
                    $code = 0;
                }
            } else {
                $result = $delegatee. ' is not in db.';
                $code = -1;
            }
            return response()->json([
                'result' => $result,
                'code' => $code,
            ]);
        }

        // 见证人投票提醒
        if ($data_type == 'account_witness_vote') {
            $account = isset($data['account']) ? $data['account'] : null;
            $witness = isset($data['witness']) ? $data['witness'] : null;
            $approve = isset($data['approve']) ? $data['approve'] : true;

            $user = WxUsers::where('username', $witness)->first();
            if ($user) {
                $settings = json_decode($user->settings, true);
                if ( isset($settings['account_witness_vote']) && $settings['account_witness_vote'] == 1) {
                    try {
                        // 引入微信SDK
                        $app = app('wechat.official_account');
                        $tmpl_id = getenv('WECHAT_TMPL_CHANGE_ID');

                        $witness_url = 'https://steemd.com/@'.$witness;
                        // 发送微信模板消息
                        $app->template_message->send([
                            'touser' => $user['wx_openid'],
                            'template_id' => $tmpl_id,
                            'url' => $witness_url,
                            'data' => [
                                'first' => $account. ' 给你'. ($approve ? '投': '撤') .'票了',
                                'keyword1' => date('Y-m-d H:i:s', time()),
                                'keyword2' => $approve ? '见证人投票' : '见证人撤票',
                                'keyword3' => '无',
                                'remark' => '点击可以查看详情',
                            ],
                        ]);
                        $result = $account . ' vote to' . $witness. ' and sent wx msg';
                        $code = 1;
                    } catch (Exception $e) {
                        $result = $e->getMessage();
                        $code = -3;
                        Log::error('send_delegate_error:'. $e->getMessage());
                    }
                } else {
                    $result = $account . ' vote to' . $witness. ' but not set witnees on';
                    $code = 0;
                }
            } else {
                $result = $witness. ' is not in db.';
                $code = -1;
            }
            return response()->json([
                'result' => $result,
                'code' => $code,
            ]);
        }
    }
}
