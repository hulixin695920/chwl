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

/**
 * @var \Illuminate\Routing\Router $router
 */

Route::get('/', function () {
    return view('welcome');
});

//$router->group([
//    'prefix' => config('common.prefix'),
//    'middleware' => ['common.response'],
//], function () use ($router) {
//    #---------------微信相关------------------#
//    $router->get('/api/get-list-data', 'ProjectController@listData');
//
//    $router->get('/api/get-wechat-user-info', 'WechatController@getUserBaseInfo');
//
//    $router->get('/api/get-wechat-qrcode', 'WechatController@getUnlimitedQrcode');
//
//    // 保存用户信息
//    $router->post('/api/save-user-info', 'UserController@saveUser');
//
//
//});
