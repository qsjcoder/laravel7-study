<?php
namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * 在 Eloquent 模型类上使用全局作用域和局部作用域进行查询
 */

 class EmailVerifiedAtScope implements Scope{
     public function apply(Builder $builder, Model $model)
     {
        // return $builder->whereNotNull();  
        return $builder->whereNull('email_verified_at');  
     }
 }