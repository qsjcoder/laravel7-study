<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    //
    public function getRouteKeyName()
    {
        // URL ：http://lv7.test/task/xiaoming  数据库中字段为name值为xiaoming的记录
        return "name";//name为字段名  以任务名称作为路由模型绑定查询字段
        // 注：如果路由模型绑定对应匹配记录不存在，将自动返回 404 响应。
    }

}
