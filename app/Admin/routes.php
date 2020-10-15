<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {
    $router->resource('project', 'ProjectController');
    $router->resource('banner', 'BannerController');
    $router->resource('user', 'UserController');
    $router->resource('feedback', 'FeedbackController');
    $router->get('/', 'HomeController@index')->name('home');

});
