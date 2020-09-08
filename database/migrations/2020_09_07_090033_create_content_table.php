<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * 
     * 添加外键：
     * 建立文章表中的 user_id 字段与用户表中的 id 之间的关联关系 ：$table->foreign('user_id')->references('id')->on('users');
     * 外键约束 级联删除和更新：比如我们删除了 users 表中的某个 id 对应记录，那么其在文章表中对应 user_id 的所有文章会被删除可以通过 onDelete 和 onUpdate 方法来实现
     * $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
     * $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade');
     * 
     * 删除外键索引:
     * $table->dropForeign(['user_id])
     * 或者通过完整的外键索引名来删除
     * $table->dropForeign('content_user_id_foreign')
     * 
     * 说明：不推荐使用外键，更不要使用外键约束功能，因为影响数据库性能，
     * 而且级联删除有可能造成非常严重的无法挽回的后果。
     * 关联关系我们建议通过业务逻辑代码来实现，比如后面介绍的 Eloquent ORM 专门提供了常见的关联关系方法。
     */
    public function up()
    {
        // 创建该迁移类的命令：php artisan make:migration create_content_table
        // laravel 能自动识别 create与table之间的字段然后自动设置为数据表明
        // 运行迁移命令： php artisan migrate完成数据表创建
        // 回滚命令： php artisan migrate:rollback

        // 创建迁移的表名要加s ???，然后模型下了类名不用加s。否则运行填充数据的命令：php artisan db:seed
        // 会报错：PDOException::("SQLSTATE[42S02]: Base table or view not found: 1146 Table 'laravel7.contents' doesn't exist")
        Schema::create('content', function (Blueprint $table) {
            $table->id()->unique();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('user_id'); //与添加外键的语句部分先后顺序执行，即在foreign语句下面也可以
            $table->string('content');
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('content');
    }
}
