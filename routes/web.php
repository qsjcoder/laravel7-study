<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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
// 灵活限制
Route::middleware('throttle:rate_limit,1')->group(function () {
    Route::get('/user', function () {
        // 在 User 模型中设置自定义的 rate_limit 属性值
    });
    Route::get('/post', function () {
        // 在 Post 模型中设置自定义的 rate_limit 属性值
    });
});
// 表单请求方法伪造 由于表单请求只支持GET和POST请求
// 所以要实现其他请求：DELETE PUT PATCH OPTIONS则需要通过表单方法伪造来完成
// 为方便起见 所以有操作都在闭包内完成
Route::get('dtask/{id}/delete',function($id){
    return '<form action="'.route('dtask.delete',[$id]).'" method="post" >
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" name="_token" value="'.csrf_token().'">
        <button type="submit">删除任务</button>
        </form>'; 
});
// 配合上面的闭包路由完成删除路由
Route::delete('dtask/{id}',function($id){
    return "Delete task id:".$id; //添加解决方法哪一行代码后点击删除任务就会返回：Delete task id:666
})->name('dtask.delete');
// 完成上面两步后，此时点击页面的的删除任务会出现419 | Page Expired页面到期错误
// 这其实是因为默认情况下，为了安全考虑，Laravel 期望所有路由都是「只读」操作的（对应请求方式是 GET、HEAD、OPTIONS），
// 如果路由执行的是「写入」操作（对应请求方式是 POST、PUT、PATCH、DELETE），
// 则需要传入一个隐藏的 Token 字段（_token）以避免[跨站请求伪造攻击]（CSRF）。
// 在我们上面的示例中，请求方式是 DELETE，但是并没有传递 _token 字段，所以会出现异常。
// 解决方法：加一行 <input typpe="hidden" name="_token" value="'.csrf_token().'">
// 还可以用csrf_field()方法 不过要去掉最外层的单引号，重新用点连接表单

// 视图
// php 
Route::get('users/{id?}', function ($id = 1) {
    return view('users.profile', ['id' => $id]);
})->name('users.profile');

// blade
Route::get('page/{id}', function ($id) {
    return view('page.show', ['id' => $id]);
})->where('id', '[0-9]+');

// css
Route::get('page/css', function () {
    return view('page.style');
});

// 模板引擎路由
Route::get('blade/master',function(){
    return view('layouts.master');
});
Route::get('blade/child',function(){
    return view('layouts.child');
});

// View Composer
Route::get('partials/header',function(){
    return view('partials.header');
});

Route::get('partials/footer',function(){
    return view('partials.footer');
});

// 获取用户请求
Route::post('request/form','RequestController@form');
// 为了测试，我们可以在 Postman 中模拟请求数据，
// 不过在测试前需要在 app/Http/Middleware/VerifyCsrfToken.php 
// 中间件中将测试路由排除在外，否则会因为 POST 请求触发 CSRF 攻击防护验证而导致请求失败：
// protected $except = [
//  'request/form*'
// ];
// 然后可以在URL中输入参数http://lv7.test/request/form?id=123&name=55
// 获取路由参数值
Route::post('request/route/{id}','RequestController@route');

// 基于laravel + vue组件实现文件异步上传
// 定义文件上传路由
//-- 用于显式上传表单  Laravel 提供的 Bootstrap 和 Vue 脚手架代码位于 laravel/ui 依赖包中，需要通过 Composer 下载安装： composer require laravel/uiyes 
Route::get('request/upload_form','RequestController@upload_form');
//-- 用于处理文件上传
Route::post('request/upload_file','RequestController@upload_file');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
