<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RequestController extends Controller
{
    //
    public function form(Request $request){
        
        // return $request->all(); //// 然后可以在URL中输入参数http://lv7.test/request/form?id=123&name=55
        // return $request->name; 就只能返回参数中name的值 或者用input方法$request->input('name');
        // 或者通过except 或 only 方法，这两个方法是相反的，一个用于排除指定字段，一个用于获取指定字段：
        // return $request->except('id');
        // return $request->only(['name', 'site', 'domain']);

        // 还可以通过has或exists方法判断某个字段是否存在
        // $id = $request->has('id')?$request->get('id'):0; //有id就返回ID没有ID就返回0
        // return $id;

        // 还可以通过 . 操作符来获取每个数组元素
        // 测试URL：http://lv7.test/request/form?id=123&name=55&books[0]=laravel6&books[1]=laravel7
        // return $request->input('books.0'); //返回laravel6
        // 或者直接用数组下标的方式访问
        // return $request->books[1];
        
        // 还可以支持更深层次的嵌套 二维数组
        // 请求URL：http://lv7.test/request/form?id=123&name=55&books[0]=laravel6&books[1]=laravel7&books[1][author]=qsj
        return $request->input('books.1.author');
        // return $request->books[1]['author'];
    }
    public function route(Request $request,$id){
        dump($request->segments());
    }
}
