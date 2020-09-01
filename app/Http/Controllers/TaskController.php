<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    //通过命令php artisan make:controller TaskController创建
    public function home()
    {
        return "这里是Controller下的TaskController创建";
    }
    public function index(){
        // web.php 中定义路由 Route::get('/task-index','TaskController@index');
        // 将数组赋值到tasks变量，并在视图 task.index （resources/views/task/index.blade.php）中渲染出来
        return view('task.index')->with('tasks',['qsj'=>'real-man','hhj'=>'nice']);
        // return view('task.index')->with('tasks',Task::all()); //或者返回所有数据库查询结果 (SQL: select * from `tasks`)
    }
    public function store(Request $request){
        $task = new Task();
        $task->title=$request->input('title');
        // 还可以通过门面获取用户输入
        // $task->title = Input::get('title');  //laravel 7貌似不可以？
        $task->description = $request->input('description');
        $task->save();
        return redirect('task/store');//重定向到GET task路由
    }
    
}
