<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 * @var \Illuminate\Routing\Router $router
 */


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

$router->group([
    'prefix' => config('common.prefix'),
    'middleware' => ['common.response'],
], function () use ($router) {
    #---------------微信相关------------------#
    $router->get('/get-list-data', 'ProjectController@listData');

    $router->get('/get-wechat-user-info', 'WechatController@getUserBaseInfo');

    $router->get('/get-wechat-qrcode', 'WechatController@getUnlimitedQrcode');

    // 保存用户信息
    $router->post('/save-user-info', 'UserController@saveUser');

    $router->get('/get-user-info', 'UserController@getInfo');

    //问题反馈
    $router->post('/add-feedback', 'FeedBackController@addFeedBack');

    $router->get('/banner-list', 'BannerController@bannerList');

    $router->post('/add-click-log', 'ClickLogController@addClickLog');

    $router->post('/add-subscribe-log', 'SubscribeController@addSubscribeLog');

    $router->get('/notice-list', 'NoticeController@getNotice');

    $router->post('/url-explain','VideoExplainController@index');

});
