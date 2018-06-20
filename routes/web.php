<?php

use TCG\Voyager\Events\Routing;
use TCG\Voyager\Events\RoutingAdmin;
use TCG\Voyager\Events\RoutingAdminAfter;
use TCG\Voyager\Events\RoutingAfter;
use TCG\Voyager\Models\DataType;
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

# 后台管理路由
Route::group([
    'middleware' => ['web'],
    'domain' => 'admin.starimg.cn'
], function () {

    Route::get('/', function () {
        return Redirect::to('admin/');
    });
    // 屏蔽注册功能
//    Auth::routes();
//    Route::get('/home', 'HomeController@index')->name('home');
    Route::group(['prefix' => 'admin'], function () {
        Voyager::routes();
        $namespacePrefix = '\\'.config('voyager.controllers.namespace').'\\';
        // 必须登录
        Route::group(['middleware' => 'admin.user'], function () use ($namespacePrefix) {
            event(new RoutingAdmin());
//            Route::get('/','Admin\StarImgAdminController@index');
            Route::get('/tongJi','Admin\StarImgAdminController@index');
            Route::get('/images','Admin\ImagesController@index');
            Route::get('/images/{id}/{type}','Admin\ImagesController@update');
            Route::get('/images/{type}','Admin\ImagesController@index');
            Route::get('/stars','Admin\StarController@index');
            Route::get('/stars_ajax','Admin\StarController@dtajax');
            event(new RoutingAdminAfter());
        });
    });

});
# api 接口路由
Route::group([
    'middleware' => ['web'],
    'domain' => 'api.starimg.cn'
], function () {

});



