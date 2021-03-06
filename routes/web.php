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
            Route::get('/downloadAvatarToCos/all','Admin\ImagesController@downloadAvatarToCos');
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
            Route::get('/updateImagesSize/{id}','Admin\ImagesController@updateInsImagesSize')->where('id', '[0-9]+');
            event(new RoutingAdminAfter());
        });
    });

});

# starimg.cn 路由
Route::group([
    'middleware' => ['web'],
    'domain' => 'starimg.cn'
], function () {
    Auth::routes();

    // site map
    Route::get('/sitemap','SiteMapController@index');

    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/', 'Frontend\HomeController@index');
    Route::get('/explore', 'Frontend\starController@explore');
    Route::get('/getImages', 'Frontend\ImagesController@index');
    Route::post('/starList', 'Frontend\starController@getStarList');
    Route::get('/starUrlList', 'Frontend\starController@getUrlStarList');

    Route::get('/pin/{id}', 'Frontend\PinController@pinDetail')->where('id', '[0-9]+');
    Route::post('/pin/{id}', 'Frontend\PinController@getPinDetail')->where('id', '[0-9]+');

    Route::get('/{name}', 'Frontend\starController@starNameDetail');
    Route::post('/{name}', 'Frontend\starController@getStarNameDetail');
    Route::get('/{name}/getImages', 'Frontend\ImagesController@getStarNameImages');
});

# api 接口路由
Route::group([
    'middleware' => ['api'],
    'domain' => 'api.starimg.cn'
], function () {

    Route::get('/getRecentImages','Api\ImagesController@getRecentImages')->where('id', '[0-9]+');
    Route::get('/starImages/{name}','Api\ImagesController@getStarNameImages');

    Route::get('/star/{id}','Api\StarController@getStarDetail')->where('id', '[0-9]+');
    Route::get('/star/{name}','Api\StarController@getStarNameDetail');

    Route::get('/searchStar','Api\StarController@searchStar');
    Route::get('/getStars','Api\StarController@getStarList');

});

