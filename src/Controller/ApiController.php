<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;
use App\Entity\User;
use EasyWeChat\Factory;

class ApiController extends Controller
{
    /**
     * @Route("/api/block/watcher", name="block_watcher")
     */
    public function blockWatcher(Request $request, LoggerInterface $logger, \Swift_Mailer $mailer)
    {
        $data = json_decode($request->getContent(), true);
        // $logger->debug(var_export( $data, true ));
        $data_type = $data[0];
        $data = $data[1];

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
                $em = $this->getDoctrine()->getManager();
                $user = $em->getRepository(User::class)->findOneBy([
                    'username' => $parent_author,
                ]);

                if ($user) {
                    $settings = json_decode( $user->getSetting() , true );
                    if ( isset($settings['replies']) && $settings['replies'] == 'on') {
                        try {
                            $email = $user->getEmail();
                            $reply_url = 'https://steemit.com/'.$parent_permlink.'/@'.$author.'/'.$permlink;
                            $sys_email = getenv('SYS_EMAIL');
                            // send email
                            $message = (new \Swift_Message('[SteemMention] You have a NEW reply!'))
                                ->setFrom($sys_email)
                                ->setTo($email)
                                ->setBody(
                                    $this->renderView(
                                        'emails/reply.html.twig',
                                        [
                                            'reply_url' => $reply_url,
                                            'author' => $author,
                                            'parent_author' => $parent_author,
                                            'body' => $body,
                                        ]
                                    ),
                                    'text/html'
                                );
                            $mailer->send($message);
                            $result = 'get ' . $parent_author . ' and sent an email to ' . $email;
                            $code = 1;
                        } catch (Exception $e) {
                            $result = $e->getMessage();
                            $code = -3;
                            $logger->error('email_error:'. $e->getMessage());
                        }
                    } else {
                        $result = 'get ' . $parent_author;
                        $code = 0;
                    }
                } else {
                    $result = $parent_author . ' is not in db.';
                    $code = -1;
                }
            }

            return $this->json([
                'result' => $result,
                'code' => $code,
            ]);
        }

        if ($data_type == 'transfer') {
            $from = isset($data['from']) ? $data['from'] : null;
            $to = isset($data['to']) ? $data['to'] : null;
            $amount = isset($data['amount']) ? $data['amount'] : null;
            $memo = isset($data['memo']) ? $data['memo'] : null;

            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository(User::class)->findOneBy([
                'username' => $to,
            ]);

            if ($user) {
                $settings = json_decode( $user->getSetting() , true );
                if ( isset($settings['transfer']) && $settings['transfer'] == 'on') {
                    try {
                        $email = $user->getEmail();
                        $transfer_url = 'https://steemit.com/@'.$to.'/transfers';
                        $sys_email = getenv('SYS_EMAIL');
                        // send email
                        $message = (new \Swift_Message('[SteemMention] You have GOT money from '.$from.' !'))
                            ->setFrom($sys_email)
                            ->setTo($email)
                            ->setBody(
                                $this->renderView(
                                    'emails/transfer.html.twig',
                                    [
                                        'transfer_url' => $transfer_url,
                                        'to' => $to,
                                        'from' => $from,
                                        'amount' => $amount,
                                        'memo' => $memo,
                                    ]
                                ),
                                'text/html'
                            );
                        $mailer->send($message);
                        $result = 'transfer to' . $to. ' and sent an email to ' . $email;
                        $code = 1;
                    } catch (Exception $e) {
                        $result = $e->getMessage();
                        $code = -3;
                        $logger->error('email_error:'. $e->getMessage());
                    }
                } else {
                    $result = 'transfer to' . $to;
                    $code = 0;
                }
            } else {
                $result = $parent_author . ' is not in db.';
                $code = -1;
            }

            return $this->json([
                'result' => $result,
                'code' => $code,
            ]);

        }
        
    }

    /**
     * @Route("/api/wx", name="api_wx")
     */
    public function wx(Request $request, LoggerInterface $logger)
    {
        $config = [
            'app_id' => getenv('WX_APPID'),
            'secret' => getenv('WX_APPSECRET'),
            'token'  => getenv('WX_TOKEN'),
            // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
            'response_type' => 'array',
            'oauth' => [
                'scopes'   => ['snsapi_userinfo'],
                'callback' => '/wx/oauth_callback',
            ],
            'log' => [
                'default' => 'dev', // 默认使用的 channel，生产环境可以改为下面的 prod
                'channels' => [
                    // 测试环境
                    'dev' => [
                        'driver' => 'single',
                        'path' => '/tmp/easywechat.log',
                        'level' => 'debug',
                    ],
                    // 生产环境
                    'prod' => [
                        'driver' => 'daily',
                        'path' => '/tmp/easywechat.log',
                        'level' => 'info',
                    ],
                ],
            ],
        ];
        $wxapp = Factory::officialAccount($config);
        $server = $wxapp->server;
        $user = $wxapp->user;

        $server->push(function($message) use ($user) {
            //$fromUser = $user->get($message['FromUserName']);
            //return "您好, {$fromUser['nickname']}！欢迎关注 overtrue!";
            switch ($message['MsgType']) {
                case 'event':
                    // return '收到事件消息';
                    if ($message['Event'] == 'subscribe') {

                    }
                    return $this->helpMsg();
                    break;
                case 'text':
                    return $this->handleText($message);
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

        // $this->sendWX($wxapp, []);
        $response = $server->serve();
        // var_dump($response);
        return $response;
    }

    private function sendWX($wxapp, $data) {
        $wxapp->template_message->send([
            'touser' => 'oUeGNtz41L35y49a_xqXGjWeBazU',
            'template_id' => getenv('WX_TEMPLATEID'),
            'url' => 'https://easywechat.org',
            'data' => [
                'title' => '这里是标题',
                'sendtime' => date('Y-m-d H:i:s', time()),
                'content' => "这里是内容\n内容\n内容\n内容\n内容\n内容\n内容\n内容\n内容\n内容\n",
            ],
        ]);
        
    }

    private function helpMsg() {
        return "回复数字进行选择：\n1. 绑定 Steem 账号\n2. 设置需要提醒的内容\n";
    }

    private function handleText($msg) {

    }
}
