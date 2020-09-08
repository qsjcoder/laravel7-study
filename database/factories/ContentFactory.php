<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\models\Content;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Content::class, function (Faker $faker) {
    return [
        //
       'content'=>Str::random(10),
       'createe_at'=>now(),
       'updated_at'=>now(),
    ];
});
