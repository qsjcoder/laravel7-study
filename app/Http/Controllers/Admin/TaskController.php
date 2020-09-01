<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    //通过命令php artisan make:controller \Admin\TaskController创建
    public function home()
    {
        return "这里是Admin下的TaskController创建";
    }
}
