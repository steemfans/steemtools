<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\WxUsers;
use Illuminate\Support\Facades\Cache;

class SteemPageController extends Controller
{
    public function post($author, $title) {
        $post = get_content_by_account_and_title($author, $title);
        if ($post) {
            $post['result']['body'] = parse_content($post['result']['body']);
            $post['result']['created'] = strtotime($post['result']['created']) * 1000;
            $data = $post['result'];
            // wx jssdk
            $app = app('wechat.official_account');
            $data['wx_config'] = $app->jssdk->buildConfig([
                'updateAppMessageShareData',
                'updateTimelineShareData',
            ], false, false, true);
            // wx share
            $default_img_url = 'https://steem.to0l.cn/img/steem.png';
            $json_metadata = json_decode($post['result']['json_metadata'], true);
            $img_url = '';
            // if (isset($json_metadata['image'])) {
            //     $img_url = $json_metadata['image'][0];
            // } else {

            // }
            if (!$img_url) {
                $img_url = $default_img_url;
            }
            $data['share_info'] = [
                'title' => $data['title'],
                'desc' => '点击使用 SteemTools 查看',
                'link' => route('steem_page_post', ['author' => $data['author'], 'title' => $data['permlink']]),
                'img_url' => $img_url,
            ];
        } else {
            $data = [];
        }
        return response()->view(
            'steempage/post',
            $data,
            200
        );
    }

    public function reply($author, $title) {
        $post = get_content_by_account_and_title($author, $title);
        if ($post) {
            // var_dump($post);die();
            $post['result']['body'] = parse_content($post['result']['body']);
            $post['result']['created'] = strtotime($post['result']['created']) * 1000;
            $data = $post['result'];
            // wx jssdk
            $app = app('wechat.official_account');
            $data['wx_config'] = $app->jssdk->buildConfig([
                'updateAppMessageShareData',
                'updateTimelineShareData',
            ], false, false, true);
            // wx share
            $default_img_url = 'https://steem.to0l.cn/img/steem.png';
            $json_metadata = json_decode($post['result']['json_metadata'], true);
            $img_url = '';
            // if (isset($json_metadata['image'])) {
            //     $img_url = $json_metadata['image'][0];
            // } else {

            // }
            if (!$img_url) {
                $img_url = $default_img_url;
            }
            $data['share_info'] = [
                'title' => '回复详情',
                'desc' => '点击使用 SteemTools 查看',
                'link' => route('steem_page_reply', ['author' => $data['author'], 'title' => $data['permlink']]),
                'img_url' => $img_url,
            ];
            // parent link
            if ($data['depth'] === 1) {
                $data['parent_link'] = route('steem_page_post', ['author' => $data['parent_author'], 'title' => $data['parent_permlink']]);
            } else {
                $data['parent_link'] = route('steem_page_reply', ['author' => $data['parent_author'], 'title' => $data['parent_permlink']]);
            }
        } else {
            $data = [];
        }
        return response()->view(
            'steempage/reply',
            $data,
            200
        );
    }
}
