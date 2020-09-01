<?php
namespace app\Http\Middleware;
class MyMiddle{
    public function __construct()
    {
        return 'Auth中间件首先需要通过我这里';
    }
}