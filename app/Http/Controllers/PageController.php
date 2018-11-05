<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\WxUsers;
use App\Model\PagesJump;

class PageController extends Controller
{
    public function jump($website) {
        $userinfo = session('wechat.oauth_user.default');
        $userid = $userinfo->id;
        $user = WxUsers::where('wx_openid', $userid)->first();
        if ($user) {
            $steem_username = $user->username;
        } else {
            $steem_username = '';
        }
        $page = PagesJump::where('keyword', $website)
                ->where('status', 1)
                ->first();
        if ($page) {
            $url = $page->url . '?id=' . $steem_username;
            $text = $page->descp;
            $sitename = $page->sitename;
        } else {
            $url = '';
            $text = '暂时没有你要找的网站';
            $sitename = '';
        }
        return response()->view(
            'page/jump',
            [
                'url' => $url,
                'website' => $website,
                'text' => $text,
                'sitename' => $sitename,
            ],
            200
        );
    }

    public function more() {
        $data = [];
        $data['pages'] = PagesJump::where('status', 1)
                ->orderBy('order_index', 'asc')
                ->get();
        return response()->view(
            'page/more',
            $data,
            200
        );
    }

    public function apply(Request $request) {
        $data = [
            'input' => [
                'sitename' => null,
                'keyword' => null,
                'url' => null,
                'descp' => null,
            ],
        ];
        if ($request->isMethod('post')) {
            $data['input'] = $request->input();
            $page = PagesJump::where('keyword', $data['input']['keyword'])->first();
            if ($page) {
                $request->session()->flash('status0', '关键词已被占用');
                return response()->view(
                    'page/apply',
                    $data,
                    200
                );
            }
            $tmp = PagesJump::orderBy('order_index', 'desc')->first();
            if ($tmp) {
                $data['input']['order_index'] = $tmp->order_index + 1;
            } else {
                $data['input']['order_index'] = 0;
            }
            $data['input']['status'] = 0;
            $page = PagesJump::create($data['input']);
            return redirect()->route('page_more')
                    ->with('status1', '申请成功, 等待审核中');
        }
        return response()->view(
            'page/apply',
            $data,
            200
        );
    }

    public function sellvote() {
        $data = [];
        return response()->view(
            'page/sellvote',
            $data,
            200
        );
    }

    public function tools() {
        $data = [];
        return response()->view(
            'page/tools',
            $data,
            200
        );
    }

}
