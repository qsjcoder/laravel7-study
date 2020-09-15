<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\models\Post;
use Faker\Generator as Faker;

$factory->define(post::class, function (Faker $faker) {
    return [
        //填充数据
        'title'=>$faker->title,
        'content'=>$faker->text,
        'user_id' =>mt_rand(1,15),
        'views' => $faker->randomDigit
    ];
});
