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

Route::any('/wechat', 'WeChatController@serve');
Route::any('/wechat_menu', 'WeChatController@menu');
Route::any('/block', 'BlockController@info');

Route::any('/page/jump/{website}', 'PageController@jump');
Route::any('/page/sellvote', 'PageController@sellvote');
Route::any('/page/tools', 'PageController@tools');

Route::any('/test', 'TestController@index');
