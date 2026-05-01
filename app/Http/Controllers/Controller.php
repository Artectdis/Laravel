<?php

namespace App\Http\Controllers;

use App\Models\Post;

abstract class Controller
{
    protected $posts;

    public function __construct()
    {
        $this->posts = Post::all();
    }
}