<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function jump($website) {
        switch($website) {
            case 'steemyy':
                $url = 'https://steemyy.com/steemit-tools/';
                $text = '@justyy';
                $sitename = 'SteemYY';
                break;
            case 'witness':
                $url = 'https://www.eztk.net/tools/my_witnesses.php';
                $text = '@oflyhigh';
                $sitename = '见证人列表';
                break;
            case 'steemgg':
                $url = 'https://steemgg.com/';
                $text = '@bobdos @bizheng @bonjovis @kanny10 @stabilowl';
                $sitename = 'SteemGG';
                break;
            default:
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

}
