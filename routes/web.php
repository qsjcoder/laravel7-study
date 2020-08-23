<?php

use Illuminate\Support\Facades\Route;

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
// 2020.08.22
// 域名为lv.test
Route::get('/', function () {
    return "<div>hello world</div>";
});
// 路由参数 http://lv7.test/user/7
Route::get('user/{id_num}',function($id){
    return "id:".$id;
});
// 可选路由参数 必须有默认值  在参数后面加上? 并赋值id的默认值为1，那么就返回默认值1
// http://lv7.test/user2  就会在浏览器中打印出1
Route::get('user2/{id?}',function($id=1){
    return "id:".$id;
});
// 路由命名 创建师徒test.blade.php
Route::get('user3/{id?}',function($id=6){
    return '用户ID:'.$id;
})->name('user.profile');
Route::get('test',function(){
    return view('test');
});

// 2020.08.23
