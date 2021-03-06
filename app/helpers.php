<?php
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

if (!function_exists('steem_per_mvests')) {
    function steem_per_mvests() {
        $api_url = getenv('STEEM_API') ? getenv('STEEM_API') : 'https://steemd.privex.io';

        $data = '{"jsonrpc":"2.0", "method":"database_api.get_dynamic_global_properties", "id":1}';
        $options = array(
        'http' =>
            array(
            'header' => "Content-Type: application/json\r\n".
                        "Content-Length: ".strlen($data)."\r\n".
                        "User-Agent:SteemMention/1.0\r\n",
            'method'  => 'POST',
            'content' => $data,
            )
        );
        $context  = stream_context_create($options);
        try {
            $result = file_get_contents($api_url, false, $context);
            $r = json_decode($result, true);
            $result = $r['result'];
        } catch (\Exception $e) {
            \Log::error('update_steem_per_mvests_failed');
            return false;
        }
        return $result['total_vesting_fund_steem']['amount'] / ($result['total_vesting_shares']['amount'] / 1e6);
    }
}

if (!function_exists('vests_to_sp')) {
    function vests_to_sp($vests, $steem_per_mvests = false) {
        if (!$steem_per_mvests) {
            $steem_per_mvests = steem_per_mvests();
        }
        if ($steem_per_mvests) {
            $vests = str_replace(',', '', $vests);
            return (float)$vests / 1e3 * (float)$steem_per_mvests;
        } else {
            return false;
        }
    }
}

if (!function_exists('post_data_steem_api')) {
    function post_data_steem_api($data = '', $api_url = 'https://steemd.privex.io') {
        $api_url = getenv('STEEM_API') ? getenv('STEEM_API') : $api_url;
        $options = array(
        'http' =>
            array(
            'header' => "Content-Type: application/json\r\n".
                        "Content-Length: ".strlen($data)."\r\n".
                        "User-Agent:SteemMention/1.0\r\n",
            'method'  => 'POST',
            'content' => $data,
            )
        );
        $context  = stream_context_create($options);
        try {
            $result = file_get_contents($api_url, false, $context);
            $r = json_decode($result, true);
        } catch (\Exception $e) {
            \Log::error('post_data_steem_api_error:');
            \Log::error($e);
            return false;
        }
        return $r;
    }
}

if (!function_exists('get_content_by_account_and_title')) {
    function get_content_by_account_and_title($account, $title) {
        $key = 'post_' . $account . '_' . $title;
        return Cache::get($key, function() use($account, $title, $key) {
            $data = '{"jsonrpc":"2.0", "method":"condenser_api.get_content", "params":["'.$account.'", "'.$title.'"], "id":1}';
            $post = post_data_steem_api($data);
            if ($post != false) {
                // 10 minutes
                Cache::put($key, $post, 10);
                return $post;
            }
            return false;
        });
    }
}

if (!function_exists('get_replies_by_account_and_title')) {
    function get_replies_by_account_and_title($account, $title) {
        $key = 'replies_' . $account . '_' . $title;
        return Cache::get($key, function() use($account, $title, $key) {
            $data = '{"jsonrpc":"2.0", "method":"condenser_api.get_content_replies", "params":["'.$account.'", "'.$title.'"], "id":1}';
            $post = post_data_steem_api($data);
            if ($post != false) {
                // 10 minutes
                Cache::put($key, $post, 10);
                return $post;
            }
            return false;
        });
    }
}

if (!function_exists('parse_content')) {
    function parse_content($content) {
        try {
            // find img url without <img> or ![]()
            $preg =  '/((http(s?):)([\/|.|\w|\s|-])*\.(?:jpg|gif|png|jpeg))\s/i';
            $replace_str = '![]($1)';
            $content = preg_replace($preg, $replace_str, $content);
            // markdown
            $parsedown = new \Parsedown();
            $content = $parsedown->text($content);
            // img proxy
            $preg =  '/<img.*?src=[\"|\']?(.*?)[\"|\']?\s.*?>/i';
            $img_proxy = getenv('IMG_PROXY') ? getenv('IMG_PROXY') : 'https://img.steemtools.top/';
            $replace_str = '<img src="'.$img_proxy.'$1">';
            $content = preg_replace($preg, $replace_str, $content);
        } catch(\Exception $e) {
            \Log::error($e);
            $content = '';
        }
        return $content;
    }
}

if (!function_exists('get_account_history')) {
    function get_account_history($account, $start = -1, $limit = 100) {
        $data = '{"jsonrpc":"2.0", "method":"account_history_api.get_account_history", "params":{"account":"'.$account.'", "start":'.$start.', "limit":'.$limit.'}, "id":1}';
        return post_data_steem_api($data);
    }
}

if (!function_exists('get_thumb_from_content')) {
    function get_thumb_from_content($content) {
        $default_thumb = 'https://steemtools.top/img/steem.png';
        try {
            $img_proxy = getenv('IMG_PROXY') ? getenv('IMG_PROXY') : 'https://img.steemtools.top/';
            $preg =  '/(http(s?):)([\/|.|\w|\s|-])*\.(?:jpg|gif|png|jpeg)/i';
            $matches = [];
            preg_match($preg, $content, $matches);
            if (isset($matches[0])) {
                return $img_proxy.$matches[0];
            } else {
                return $default_thumb;
            }
        } catch(\Exception $e) {
            \Log::error($e);
            return $default_thumb;
        }
    }
}

if (!function_exists('get_descption_from_content')) {
    function get_description_from_content($content, $limit = 40) {
        $content = strip_tags($content);//去除html标记
        $content = str_replace(PHP_EOL, '', $content);
        $pattern = "/&[a-zA-Z]+;/";//去除特殊符号
        $content = preg_replace($pattern,'',$content);
        return Str::limit($content, $limit);
    }
}

if (!function_exists('get_blogs_by_account')) {
    function get_blogs_by_account($account, $start = 0, $limit = 10) {
        $key = 'account_blog_list_' . $account . '_' . $start . '_' . $limit;
        return Cache::get($key, function() use($account, $start, $limit) {
            $data = '{"jsonrpc":"2.0", "method":"follow_api.get_blog", "params":{"account":"'.$account.'", "start_entry_id":'.$start.', "limit":'.$limit.'}, "id":1}';
            $posts = post_data_steem_api($data);
            if ($posts != false) {
                // 5 minutes
                Cache::put($key, $posts, 5);
                return $posts;
            }
            return false;
        });
    }
}
