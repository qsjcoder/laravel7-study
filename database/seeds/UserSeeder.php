<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //输入命令：php artisan db:seed 运行run函数用以填充数据
        // $this->call(UserSeeder::class);
        // DB::table('users')->insert([
        //     'name'=>str::random(10),
        //     'email'=>Str::random(10).'@qq.com',
        //     'password'=>Hash::make('password'),
        //     'created_at'=>Date::now('Asia/Shanghai'),//可以单独设置时区
        //     'updated_at'=>date('Y-m-d H:m:s',$_SERVER['REQUEST_TIME']),
        // ]);

        // 通过调用模型工厂实现
        factory(\App\User::class,5)->create(); //向users表中插入5条数据
    }
}
