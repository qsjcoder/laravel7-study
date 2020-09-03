<?php

namespace App\Http\ViewComposers; 

use App\Post; 
use Illuminate\Contracts\View\View; 

// 通过自定义类实现更加灵活的数据预设
class RecentPostsComposer 
{

    private $posts; 

    public function __construct(Post $posts) { 
        $this->posts = $posts; 
    }

    public function compose(View $view) { 
        $view->with('posts', $this->posts->recent());
    }
}