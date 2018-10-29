<?php
if (! function_exists('steem_per_mvests')) {
    function steem_per_mvests() {
        $url = 'https://api.steemit.com';

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
            $result = file_get_contents($url, false, $context);
            $r = json_decode($result, true);
            $result = $r['result'];
        } catch (\Exception $e) {
            \Log::error('update_steem_per_mvests_failed');
            return false;
        }
        return $result['total_vesting_fund_steem']['amount'] / ($result['total_vesting_shares']['amount'] / 1e6);
    }
}

if (! function_exists('vests_to_sp')) {
    function vests_to_sp($vests, $steem_per_mvests = false) {
        if (!$steem_per_mvests) {
            $steem_per_mvests = steem_per_mvests();
        }
        if ($steem_per_mvests) {
            $vests = str_replace(',', '', $vests);
            return (float)$vests / 1e6 * (float)$steem_per_mvests;
        } else {
            return false;
        }
    }
}
