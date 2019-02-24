<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::any('/wechat', 'WeChatController@serve')->name('wechat_api');
Route::any('/wechat_menu', 'WeChatController@menu')->name('wechat_menu');
Route::any('/wechat_menu_test', 'WeChatController@testmenu')->name('wechat_menu_test');
Route::any('/block', 'BlockController@info')->name('block_api');

Route::group(['middleware' => ['web', 'wechat.oauth:default,snsapi_userinfo']], function () {
    Route::any('/page/jump/{website}', 'PageController@jump')->name('page_jump');
    Route::any('/page/more', 'PageController@more')->name('page_more');
    Route::any('/page/apply', 'PageController@apply')->name('page_apply');
    Route::any('/page/sellvote', 'PageController@sellvote')->name('page_sellvote');
    Route::any('/page/tools', 'PageController@tools')->name('page_tools');

    Route::any('/account/selector', 'AccountController@selector')->name('account_selector');
    Route::any('/account/unbind/{username}', 'AccountController@unbind')->name('account_unbind');
    Route::any('/account/config/{username}', 'AccountController@config')->name('account_config');

    Route::any('/steem/callback', 'SteemController@callback')->name('steem_callback');

    Route::get('/steempage/post/{author}/{title}', 'SteemPageController@post')->name('steem_page_post');
});

Route::any('/test', 'TestController@index');
