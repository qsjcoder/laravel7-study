<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersAddNickname extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            //增加昵称
            $table->string('nickname',100)->after('name')->nullable()->comment('昵称');
            // nickname 字段是一个长度为 100 的字符串，该字段会插入到 name 字段后面，允许为空，注释信息是用户昵称。
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //已存在就删除
            $table->dropIfExists('nickname');
        });
    }
}
