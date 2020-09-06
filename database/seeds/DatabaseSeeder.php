<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 运行数据填充命令 php artisan db:seed 会执行该run函数
        // 或者指定运行填充器的run方法：php artisan db:seed --class=UserSeeder
        $this->call(UserSeeder::class);

        // 生成填充器类 php artisan make:seeder UserSeeder
    }
}
