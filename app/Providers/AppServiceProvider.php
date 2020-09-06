<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 解决迁移数据库时报错unique太长的问题
        Schema::defaultStringLength(191);

        //在视图之间共享变量
        view()->share("sitename",'中国节日倒计时');
        view()->share('siteurl','china-day.cn');
        // 上面的两个变量在所有视图件都可以访问，这当然有点浪费

        // 为了解决上面的问题，通过View Composer预设视图组件数据变量
        view()->composer('partials.header',function($view){
            $view->with('posts','一个post变量');
        });

        // 通过数向多个组件输出
        view()->composer(['partials.header','partials.footer'],function($view){
            $view->with('posts_array','数组输出的变量');
        });

        // 通过通配符制定多个视图文件
        view()->composer('partials.*', function ($view) { 
            $view->with('posts_all', "通过通配符匹配的变量"); 
        });
    }
}
