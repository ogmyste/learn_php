<?php

namespace App\Controllers;

use App\DB;
use App\Models\Post;
use App\Models\User;

class PublicController
{
    public function index()
    {
        $title = 'World';
        $posts = Post::where('category', 'world');
        dump($posts);
        view('index', compact('title', 'posts'));
    }

    public function us()
    {
        $title = 'U.S';
        $posts = Post::where('category', 'us');
        view('us', compact('title', 'posts'));
    }

    public function tech()
    {
        $title = 'Technology';
        $posts = [
            (object) [
                'title' => 'Learning to build a web application with PHP',
                'created_at' => 'November 15, 2026',
                'author' => 'Blog team',
                'body' => 'Routes connect a web address to a controller. The controller prepares the data and passes it to a view, keeping the application easier to understand and extend.',
            ],
            (object) [
                'title' => 'Why version control matters',
                'created_at' => 'November 14, 2026',
                'author' => 'Blog team',
                'body' => 'Small Git commits make it easier to follow changes, compare working versions and fix mistakes. A clear commit message explains what changed in the project.',
            ],
        ];
        view('tech', compact('title', 'posts'));
    }

    public function test() {
        $db = new DB();
    }

    public function form() {
        
        view('form');
    }

    public function answer(){
        dump($_GET, $_POST);
    }
}
