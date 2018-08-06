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
            Route::get('/downloadHttpImages/{id}','Admin\ImagesController@downloadHttpImages');
            Route::get('/images','Admin\ImagesController@index');
            Route::post('/images/deleteSome','Admin\ImagesController@deleteSome');
            Route::post('/images/{id}/{type}','Admin\ImagesController@update')->where('id', '[0-9]+');
            Route::get('/images/{type}','Admin\ImagesController@index');
            Route::get('/images/{star_id}/{type}','Admin\ImagesController@starImage')->where('star_id', '[0-9]+');
            Route::get('/stars','Admin\StarController@index');
            Route::get('/stars/new','Admin\StarController@create');
            Route::post('/stars/store','Admin\StarController@store');
            Route::get('/stars/{id}/edit','Admin\StarController@edit')->where('id', '[0-9]+');
            Route::get('/stars/{id}','Admin\StarController@show')->where('id', '[0-9]+');
            Route::get('/stars_ajax','Admin\StarController@dtajax');
            Route::get('/images_ci','Admin\ImagesController@imageDetect');
            event(new RoutingAdminAfter());
        });
    });

});
# api 接口路由
Route::group([
    'middleware' => ['web'],
    'domain' => 'api.starimg.cn'
], function () {
    Route::get('/', 'Frontend\ImagesController@index');
    Route::get('/{id}', 'Frontend\ImagesController@getStarImages');
    Route::get('/starList', 'Frontend\ImagesController@getStarList');
});


# starimg.cn 路由
Route::group([
    'middleware' => ['web'],
    'domain' => 'starimg.cn'
], function () {
//    Auth::routes();
    Route::get('/', 'Frontend\HomeController@index');
    Route::get('/getImages', 'Frontend\ImagesController@index');
    Route::get('/{id}', 'Frontend\ImagesController@getStarImages');
    Route::get('/starList', 'Frontend\ImagesController@getStarList');
});

