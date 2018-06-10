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
        Route::get('/images','Admin\ImagesController@index');
    });
});
# api 接口路由
Route::group([
    'middleware' => ['web'],
    'domain' => 'api.starimg.cn'
], function () {

});



