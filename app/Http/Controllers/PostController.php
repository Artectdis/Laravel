<?php

namespace App\Http\Controllers;

class PostController extends Controller
{
    public function index()
    {
        // $this->posts is now available because it was set by the parent constructor
        return view('posts.index', ['posts' => $this->posts]);
    }
}
