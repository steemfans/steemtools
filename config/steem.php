<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application settings
    |--------------------------------------------------------------------------
    |
    | Basic Steem application name, version and community settings.
    |
    */
    'app' => [
        // application name.
        'name' => env('STEEM_APP_NAME', 'steemtools.app'),

        // application version.
        'version' => env('STEEM_APP_VERSION', '1.0.0'),

        // application community name.
        'community' => env('STEEM_COMMUNITY', null),
    ],

    /*
    |--------------------------------------------------------------------------
    | Steem RPC URL
    |--------------------------------------------------------------------------
    |
    | HTTPS steem RPC URL.
    |
    */

    'rcp' =>  [
        'url' => env('STEEM_RPC_URL', 'https://api.steemit.com'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Steem Connect Configuration
    |--------------------------------------------------------------------------
    |
    | OAuth SteemConnect SDK client configuration.
    |
    */

    'sc2' => [
        // OAuth client ID.
        'client_id' => env('STEEM_SC2_ID', null),
        // OAuth client secret.
        'client_secret' => env('STEEM_SC2_SECRET', null),

        // OAuth scopes. Scopes should be comma delimited.
        'scopes' => env('STEEM_SC2_SCOP', 'offline,login,vote,comment,delete_comment,comment_options,custom_json,claim_reward_balance'),

        // OAuth return URL.
        'return_url' => env('STEEM_SC2_RETURN_URL', 'http://localhost:8000/steem/callback'),
    ],
];
