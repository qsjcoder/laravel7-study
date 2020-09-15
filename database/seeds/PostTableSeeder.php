<?php

use App\models\Post;
use Illuminate\Database\Seeder;

class PostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //工厂填充数据
        factory(Post::class,10)->create();
    }
}
