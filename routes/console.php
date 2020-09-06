<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

// 基于闭包实现Artisan命令
Artisan::command('welcome:message_route',function(){
    $this->info('热烈欢迎您！'); //运行php artisan welcome:message_route 就会在控制台输出“热烈欢迎您！”
})->describe('打印欢迎信息'); //描述信息在运行php artisan list可以查看到
