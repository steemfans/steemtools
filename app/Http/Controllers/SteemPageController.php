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
            $parsedown = new \Parsedown();
            $post['result']['body'] = $parsedown->text($post['result']['body']);
            $post['result']['created'] = strtotime($post['result']['created']) * 1000;
            $data = $post['result'];
        } else {
            $data = [];
        }
        return response()->view(
            'steempage/post',
            $data,
            200
        );
    }

}
