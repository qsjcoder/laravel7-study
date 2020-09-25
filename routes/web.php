<?php
namespace App;

use App\models\Content;
use App\models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

// 数据库
// 原生statement语句
Route::get('statement',function(){
    // $sql = DB::statement('select * from users');// 成功执行sql成功返回true，并不能返回查询结果
    // dd($sql); 
    // $sql2 = DB::statement('insert into users(name,email,password) values("qsj","qsj@1024.com","123") ');
    // dd($sql2);
});
Route::get('select',function(){
   $sql1 = DB::select('select * from `users`');
   dd($sql1);
});
// 原生的curd都有DB门面下相关函数相对应  insert、update、delete与sql原生语句写法一致

// 使用查询构建器进行增删改查
Route::get('curd',function(){
// 查询记录
    // 查询数据表中所有数据
    //  dd(DB::table('users')->get()); //dd()函数会结束脚本exit(1)就不能执行下面的语句
    dump(DB::table('users')->get());
    // name :Christelle Ziemann
    dump(DB::table('users')->where('name','Christelle Ziemann')->get());
    // 返回查询结果中第一条记录
    dump(DB::table('users')->first());
    // 默认返回所有字段，返回指定字段用select方法    thinkPHP时用field方法
    dump(DB::table('users')->select('id','name')->get());//这样就会只返回指定字段的数据

// 插入记录
    // insert()方法
    $insert = DB::table('content')->insert([
        'user_id'=>random_int(1,10),//1~10之间的随机数1和10都能被取到 
        'content'=>"hello world",
        'created_at'=>now()
    ]);
    dump($insert);//插入成功返回true

    // 在查询后返回对应记录的主键id
    $insertGetId = DB::table('content')->insertGetId([
        'user_id'=>random_int(10,20),'content'=>bcrypt('i love you'),'updated_at'=>now()
    ]);
    dump($insertGetId);//输出对应主键id
    
    // 一次插入多条数据
    $insert = DB::table('content')->insert([
        ['user_id'=>random_int(10,20),'content'=>bcrypt('i love you'),'updated_at'=>now()],
        ['user_id'=>random_int(10,20),'content'=>bcrypt('i love you too'),'updated_at'=>now()],
    ]);



});
Route::get('update',function(){
// 更新记录
        // update()返回受影响的行数 //where('id','>',6)第二个符号参数省略的话默认是等于
        $update = DB::table('content')->where('id',1)->update([
            'content'=>"hello my love"
        ]);
        // 如果修改的内容与原来的相同，那么就不会执行 即返回影响行数为0
        dump("受影响的行数：",$update);

        // 如果数值字段更新的话还可以用increment和decrement用于数值增减操作
        // 默认步长是1，也可以通过第二个参数指定步长
        $increment1 = DB::table('content')->where('id',5)->increment('user_id');//user_id +1 
        dump($increment1); //返回受影响函数
        $increment2 = DB::table('content')->where('id',6)->increment('user_id',5);//user_id + 5
        dump($increment2);
        $decrement = DB::table('content')->where('id',20)->decrement('user_id',2);//user_id - 2
        dump($decrement);
});
// 删除记录
Route::get('delete',function(){
    $delete = DB::table('content')->whereBetween('id',[25,28])->delete();
    dump($delete);//返回受影响的行数    

    // 如果要清空整张表可以通过不指定where条件来实现
    // $alldelete = DB::table('test')->delete();   
    
    // 如果想在清空记录后重置自增ID，可以通过truncate()方法
    $trun = DB::table('test')->truncate();

    // $Truncate = DB::table('content')->whereBetween('id',[24,30])->truncate();
    // dump($Truncate);
    // 实验证明：truncate函数会忽视前=前面的条件语句会直接清空整张表，并重置id
});

// 查询构建起进阶
Route::get('advance',function(){
    // 用value返回指定查询字段的值
    $value = DB::table('content')->where('id',1)->value('content');
    dump($value);//直接返回id=1的content字段的值，没有其他多余数据

    // 判断某个字段是否存在可以通过exists()方法
    $exists = DB::table('content')->where('content',"hello world")->exists();
    dump($exists);//存在返回true，不存在返回false

    // 与上面的相反的函数doesntExist() 不存在返回true存在返回false
    $doesntExists = DB::table('content')->where('content','hello world')->doesntExist();
    dump($doesntExists);

    // 以主键 ID 值为键，以某个字段值为值构建关联数组
    $array = DB::table('users')->where('id',"<",10)->pluck('email','id');//第一个参数为值，第二个参数为键(可以省略，然后生成的数组下标默认从数字0开始)
    dump($array);
    // 有的时候，我们从数据库返回的结果集比较大，一次性返回进行处理有可能会超出 PHP 内存限制，
    // 这时候，我们可以借助 chunk 方法将其分割成多个的组块依次返回进行处理：
    $names = [];
    DB::table('users')->orderBy('id')->chunk(5, function ($users) use (&$names) {
        foreach ($users as $user) {
            $names[] = $user->name;
        }
    });
    // 以上代码的意思是对 users 按照 id 字段升序排序，然后将获取的结果集每次返回5个进行处理，将用户名依次放到 $names 数组中。

    // 聚合函数
    // 在开发后台管理系统时，经常需要对数据进行统计、求和、计算平均值、最小值、最大值等，
    // 对应的方法名分别是 count、sum、avg、min、max：
    $num = DB::table('users')->count();       # 计数     9
    $sum = DB::table('users')->sum('id');     # 求和    45
    $avg = DB::table('users')->avg('id');     # 平均值   5
    $min = DB::table('users')->min('id');     # 最小值   1
    $max = DB::table('users')->max('id');     # 最大值   9
});
Route::get('array',function(){
    // 测试reset函数:将数组的指针指向第一个元素 常配合next、current等函数使用
    $arr = DB::table('content')->get();
    dump($arr);
    dump(reset($arr));
});
// 高级Where查询
    
Route::get('where',function(){
    // 模糊查询like
    $like =  DB::table('content')->where('content','like','%hello%')->get();
    dump($like);

    // and查询
   $and = DB::table('users')->where('id','>',1)->where('id','<',20)->get();
   dump($and);
    // 用数组表示and语句 where id >10 and id <20
    $arr_and = DB::table('users')->where([
        ['id','<',20],
        ['id','>',10]
    ])->get(); 
    dump($arr_and);

    // or语句 where id >10 or user_id <20
   $oWhere = DB::table('content')->where('id','<',20)->orWhere('user_id','>',10)->get();
   dump($oWhere);

    // between语句 在一些涉及数字和时间的查询中，between语句可以派上用场
    // where id between 1 and 10
    $whereBetween = DB::table('users')->whereBetween('id',[1,10])->get();
    dump($whereBetween);

    // whereNotBetween 用于获取不在指定区间的数据库记录
    $whereNotBetween = DB::table('users')->whereNotBetween('id',[10,30])->get();
    dump($whereNotBetween);

    // in查询 用whereIn方法，用于需要查询的字段值是某个序列集合的子集的时候
    // 原生语句：where user_id in(10,15,16)   个人觉得此方法与与select方法类似
    $whereIn = DB::table('content')->whereIn('user_id',[10,15,16])->get();
    dump($whereIn);
    // 与之对应的还有一个whereNotIn方法

    // null查询  返回指定字段为空值得数据集 where created_at is null 
    $whereNull = DB::table('content')->whereNull('created_at')->get();
    dump($whereNull);
    // 与之相反的还有：whereNotNull  sql: where email_verified_at is not nulll
    // DB::table('users')->whereNotNull('email_verified_at')->get();

    // 日期查询
    $whereYear = DB::table('users')->whereYear('created_at',2020)->get();// 返回在2020年创建的数据
    dump($whereYear);
    // 月 天 时等等类似
    // DB::table('posts')->whereMonth('created_at', '11')->get();    # 月
    // DB::table('posts')->whereDay('created_at', '28')->get();      # 一个月的第几天
    // DB::table('posts')->whereDate('created_at', '2018-11-28')->get();  # 具体日期
    // DB::table('posts')->whereTime('created_at', '14:00')->get();  # 时间
    // 上面这几个方法同时还支持 orWhereYear、orWhereMonth、orWhereDay、orWhereDate、orWhereTime。

    // 字段之间比较查询,即两列之间比较 whereColumn  sql:where id < user_id
    $whereColumn = DB::table('content')->whereColumn('id','<','user_id')->get();
    dump($whereColumn);

    // JSON查询
    // 从 MySQL 5.7 开始，数据库字段原生支持 JSON 类型，对于 JSON 字段的查询，和普通 where 查询并无区别，只是支持对指定 JSON 属性的查询：
    // 假如有一个字段是options，并且有键language，值为en的数据
    // DB::table('users')->where('options->language','en')->get();
    // 如果属性字段是个数组，还支持通过 whereJsonContains 方法对数组进行包含查询：
    // DB::table('users')
    // ->whereJsonContains('options->languages', 'en_US')
    // ->get();
    
    // DB::table('users')
    //     ->whereJsonContains('options->languages', ['en_US', 'zh_CN'])
    //     ->get();
        dump("--------------高级查询------------------");
    // 用闭包函数函数完成复杂的多条件查询
    // 原生语句 select * from content where id<=20 or (id>5 and user_id<15 and updated_at <'2020-09-08 23:03' )
    $closer = DB::table('content')->where('id','<',20)->orWhere(function($query){
        $query->where('id','>',5)->where('user_id','<',15)->whereDate('updated_at','<','2020-09-08')->whereTime('updated_at','<','23:03');
    })->get();
    dump($closer);

    // whereExists查询 返回两个表中id相等的字段
    // sql : select * form `users` where exists (select 1 from `content` content.id = users.id)
    $whereExists = DB::table('users')->whereExists(function($query){
        $query->select(DB::raw(1))->from('content')->whereRaw('content.id = users.id');
    })->get();
    dump($whereExists);
    
    // 子查询关联不同的表
    // sql: select * from users where in (select id from content where created_at is not null )
    // 用构建器：
    // $users = DB::table('users')->whereNotNull('email_verified_at')->select('id');
    // $posts = DB::table('posts')->whereInSub('user_id', $users)->get();
});

Route::get('join',function(){
        //连接查询
    //-- 内连接：使用比较运算符进行表间的连接，查询与连接条件匹配的数据
    // -可分为等值连接和不等连接
    // -等值连接(=)：select * from posts p inner join users u on p.user_id=u.id
    // 不等值连接(<,>,<>[不等于]):select * from posts p inner join users u on p.user_id <> u.id 
    // --外连接  outer关键字可以省略
    // -左连接：返回左表中的所有行，如果左表中的行在右表中没有匹配行，则返回结果中右表中的对应列返回空值，如 select * from posts p left (outer) join users u on p.user_id = u.id
    // 右连接：与左连接相反，返回右表中的所有行，如果右表中的行在左表中没有匹配行，则结果中左表中的对应列返回空值，如 select * from posts p right join users u on p.user_id = u.id
    // 全连接：返回左表和右表中的所有行。当某行在另一表中没有匹配行，则另一表中的列返回空值，如 select * from posts p full join users u on p.user_id = u.id
    // 交叉连接：也称笛卡尔积，不带 where 条件子句，它将会返回被连接的两个表的笛卡尔积，返回结果的行数等于两个表行数的乘积，
    // 如果带 where，返回的是匹配的行数。如 select * from posts p cross join users u on p.user_id = u.id

    // 创建并填充posts表 ：php artisan make:migration create_posts_table --create=posts
    // 然后运行: php artisan migrate创建数据表
    // 在models目录下创建模型： php artisan make:model \models\Post
    // 为模型创建工厂类 php artisan make:factory PostFactory --model=post
    // 模型工厂编写好后 创建填充类： php artisan make:seeder PostTableSeeder
    // 编写好填充器类后 运行填充器：php artisan db:seed

    // 开始用构建器查询：
    // 内连接：
    $innerJoin = DB::table('posts')->join('users','users.id','=','posts.user_id')
    ->select('posts.*','users.name','users.id','users.email')->get();
    dump($innerJoin);
    // 左连接：
    $leftJoin = DB::table('posts')->leftJoin('users','users.id','=','posts.user_id')
    ->select('posts.*','users.name','users.id','users.email')->get();
    dump($leftJoin);
    // 右连接与左连接类似，只是基表在是右边的表
    // 其它连接语句
    // 上面三种是比较常见的连接语句，查询构建器没有提供单独的方法支持全连接，
    // 但是有对交叉连接的支持，对应的方法 crossJoin，使用方法如上面几种查询类似，

    // 更加复杂的连接条件用匿名函数组装连接查询的条件来构建查询语句
    // select posts.* , users.name,users.email from posts inner join users on users.id
    // =posts.user_id and users.email_verified_at is not null where posts.views>0
     $join = DB::table('posts')->join('users',function($sql){
        $sql->on('users.id','=','posts.user_id')->whereNotNull('users.email_verified_at');
     })->select('posts.*','users.name','users.email')->where('posts.views','>',0)->get();
     dump($join);

    //排序
    $orderBy = DB::table('posts')->orderBy('id','desc')->get();
    dump($orderBy);

    $orderByRaw = DB::table('posts')->orderByRaw('id desc')->get();
    dump($orderByRaw);

    // 随机排序
    $inRandomOrder = DB::table('posts')->inRandomOrder()->get();
    dump($inRandomOrder);

});
// Eloquent模型
Route::get('post',function(){
    // 返回一张表的所有记录
    $posts = Post::all();
    dump($posts);
    // 遍历出集合
    foreach($posts as $v){
        // dump($v);
        // dump($v->title);
    }
    // 和查询构建器一样，如果结果集很大的话，也可以通过chunk方法分块查询结果
    Post::chunk(10,function($postss){//因为前面Post已经申明类，所以匿名函数类的参数时完全重新定义的，与上面的没关系
        foreach($postss as $post ){
            // dump($post->title);
            if($post->views ==0){
                echo "views : 0";
                continue;
            }else{
                dump($post->title.':'.$post->views);
            }
        }
    });
    dump('----------cursor()--------------');
    // 还可以用cursor方法每次只获取一条查询结果，从而最大限度减少内存消耗
    foreach(Post::cursor() as $post){
        // dump($post->title.':'.$post->content);
    }

    // 获取指定条件查询结果：where 和 select方法
    $postWhere = Post::where('views','>',5)->select('id', 'title', 'content')->get();
    dump($postWhere);

    dump('----------排序和分页----------------');
    $limit = Post::where('views','>',0)->orderBy('id','desc')->offset(10)->limit(5)->get();
    foreach($limit as $v){
        dump($v->id);
    }

    // 获取单条记录
    $singleRecord = Post::where('user_id',1)->first();
    dump($singleRecord);

    // 如果查询条件的主键是id的话，还可以通过find方法查询
    $findRedord = Post::find(1);
    dump($findRedord);

    // 模型类型为空时会返回null，如果想返回404 可用findOrFail()方法或者firstOrFail()
    $findOrFail = Post::findOrFail(1);//失败后 后面的语句都不能执行
    dump($findOrFail);
    dump(666);

    
});
// firstOrCreate 方法在设置完模型属性后会将该模型记录保存到数据库中，而 firstOrNew 不会：
Route::get('post_news',function(){
    $firstC = Post::firstOrCreate([
        'user_id'=>'1',
        'title'=>'firstOrCreate测试文章标题',
        'content'=>'firstOrCreate测试内容'
    ]);
    dump($firstC);
    // Add [user_id] to fillable property to allow mass assignment on [App\models\Post].

});

// 通过命令插入数据：
// 1、php artisan tinker
// 2、use App\Models\Post;
// 3、$post = new Post;
// 4、$post->title="测试标题";
// 5、$post->content="测试文章";
// 6、$post->save();

// 删除数据
Route::get('del',function(){
    // $post = Post::find(20);
    // $post->delete(); //删除id为21的数据，并返回其数据
    // dump($post);//会返回id为21的数据

    // 传入数组批量删除
    $post2 = Post::destroy([15,16,17]);
    dump($post2);//返回删除成功的行数

    // 也可以通过查询构建器的方式删除指定记录
    // $user = User::where('id',15)->first();
    // $user->delete();
});

Route::post('guard',function(Request $request){
    // $post =  new Post($request->all());
    $post = new Post([
        'title' => '测试文章标题', 
        'content' => '测试文章内容'
    ]);
    $post->user_id = 0;
    $post->save();
});

// 测试加密和解密
Route::get('crypt',function(){
    // 加密
    $encrypt =  encrypt(123456789);
    // 随机的
    dump($encrypt); //eyJpdiI6IkdzdDkvOUhmd2VjYXQraGZMVU8zSkE9PSIsInZhbHVlIjoiTlplZFhvVmxDZm9WWENqeEdqL2xBUT09IiwibWFjIjoiMGVjNGZkZDQ0MTgwMzk4N2U1MGVmOGY0ZWM4NGU1YmYyMzU2NTdlMzhkNTlm 

    // 解密
    $decrypt = decrypt($encrypt);
    dump($decrypt);//123456789   
});


Route::get('getall',function(){
    $data1 = DB::table('posts')->where('views',3)->get();//返回集合 集合内再装下数组
    $data2 = DB::table('posts')->where('views',3)->get()->all();//直接返回数组
    dump($data1,$data2);
});

Route::get('views',function(){
    // $data['test'] =['name'=>'qjs','work'=>'programer']; 
    $data=666;   //视图中报错
    $data2 = ['num'=>555]; //能在视图中正常输出
    return view('welcome',$data2);  //输出的都是数组，
});
Route::get('testscope',function(){
    $data =  User::all();//通过模型查询表中所有数据
    // 通过Telescope调试工具可以看到Queries中生成了一条查询数据：
    // select * from `users` where `email_verified_at` is null
    //这是由User.php和EmailVerifIedAtScope.php连个文件共同来完成的效果
    dump ($data);//结果也就返回了表中所查询字段为空的数据
});
