<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     *  批量赋值时一般建议使用白名单
     */
    //批量赋值的属性 白名单 默认为空
    protected $fillable = [];

    // 不使用批量赋值的字段 黑名单 默认属性为*，即所有字段都不会应用批量赋值
    // protected $guard = ['*'];
    // 设置user_id字段为黑名单字段
    protected $guard = ['user_id'];
    // 然后定义post请求类型路由：guard
}
