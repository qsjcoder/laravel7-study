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
    dd($_SERVER);
    // return "<div>hello world</div>";
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

// 路由分组
// 2020.08.23
// 所谓路由分组，其实就是通过 Route::group 将几个路由聚合到一起，然后给它们应用对应的共享特征：
Route::group([],function(){ //空组
    Route::get('hello',function(){
        return "Hello";
    });
    Route::get('world',function(){
        return "World";
    });
});
// 认证组(Auth) 创建Auth中间件
Route::middleware('MyMiddle')->group(function(){
    Route::get('dashboards',function(){
        return view('dashboards');//在views文件夹下创建dashboard.blade.php视图文件
    });
    Route::get('acount',function(){
        return view('acount');
    });
});
// 2020.08.24
// 路由路径前缀
Route::prefix('qsj')->group(function(){
    Route::get('/',function(){  //http://lv7.test/qsj
        return "qsj首页"; 
    })->name('qsj.index');
    Route::get('user',function(){ //http://lv7.test/qsj/user
        return "qsj 用户页";
    });
});
// 子域名路由  hosts文件中添加127.0.0.1 admin.lv7.test 以及在Apache或者nginx中添加映射关系
Route::domain('admin.lv7.test')->group(function(){
    Route::get('/',function(){
        return view('welcome');
    });
    Route::get('/user',function(){
        return "admin 用户管理页";
    });
});
// 通过参数方式设置子域名  例：https://xiaomi.tmall.com
Route::domain('{account}.lv7.test')->group(function(){
    Route::get('/',function($account){
        return '子域名:'.$account; 
    });
});

// 访问通过php artisan make:controller TaskController创建的控制器
Route::get('/task','TaskController@home');
//http://lv7.test/task-admin 访问Admin目录下的控制器
Route::get('/task-admin','Admin\TaskController@home');

Route::get('/task-index','TaskController@index');

// 获取用户输入
Route::get('task/create','TaskController@create');//渲染一个任务提交表单
Route::get('task/create','TaskController@store');//存储提交的任务数据

// 资源控制器路由 PostController.php
Route::get('post','postcontroller@index')->name('post.index');
Route::get('post','postcontroller@store')->name('post.store');
// 通过 Artisan 命令 php artisan route:list 查看应用的所有路由：
// 一次注册PostController资源控制器中所有包含的路由
Route::resource('post','PostController');

// 2020.08.31
// 路由模型绑定
// 根据资源ID查询资源信息
// Route::get('task/{id}',function($id){
    // $task = \App\Models\Task::findOrFail($id);  需要先建立Models文件夹以及Task模型
// });
// 隐式绑定
Route::get('task/{task}',function(\App\Models\Task $task){
     dd("task:",$task,"第三块"); //dd() 函数中的每个参数都当占一块面积输出
});

// 显示绑定
// 显式绑定需要手动配置路由模型绑定，通常需要在 App\Providers\RouteServiceProvider 的 boot() 方法中新增如下这段配置代码：
// public function boot()
// {
//     // 显式路由模型绑定
//     Route::model('task_model', Task::class);

//     parent::boot();
// }
// 兜底路由
Route::fallback(function(){
    return "当所有URL请求无法匹配时，兜底路由生效";
    // 例http://lv7.test/666  就会返回上面这句话
});
// 频率限制
Route::middleware('throttle:5,1')->group(function(){
    Route::get('/limit_route',function(){
        return "该组内的路由1分钟内只能访问5次，超出次数限制后就会返回429错误Too Many Requests";
    });
});
