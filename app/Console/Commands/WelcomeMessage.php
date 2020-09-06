<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * 命令类 通过php artisan make:command WelcomeMessage --command=welcome:message  创建
 * 
 * 打开 app/Console/Kernel.php，将新创建的命令类 WelcomeMessage 添加到 $commands 完成注册
 * protected $commands = [
 *   App\Console\Commands\WelcomeMessage::class
 *  ];
 */
class WelcomeMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'welcome:message {name : 用户名} {--city= : 来自的城市}';
    // 在命令行输入php artisan welcome:message 黑客 --city=成都 
    // 控制台就会输出： 欢迎来自成都的黑客
    // 需要注意的时{}内的冒号两侧都需要留一个空格，否则就成了参数名/选项名的一部分了

    // 迁移命令例子：
    // protected $signature = 'make:migration {name : The name of the migration}
    // {--create= : The table to be created}
    // {--table= : The table to migrate}
    // {--path= : The location where the migration file should be created}
    // {--realpath : Indicate any provided migration file paths are pre-resolved absolute paths}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     * 命令具体逻辑放在这里
     */
    public function handle()
    {
        // $this->info("欢迎使用黑客自定义的命令"); //输入命令：php artisan welcome:message就会在控制台输出该语句
        $this->info('欢迎来自'.$this->option('city').'的'.$this->argument('name'));
        // return 0;
    }
}
