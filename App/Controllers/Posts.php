<?php

namespace MVCFramework\App\Controllers;

use \MVCFramework\Core\View;
use \MVCFramework\App\Models\Post;

class Posts extends \MVCFramework\Core\Controller
{
    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction(): void
    {
        $posts = Post::getAll();
//        echo 'Hello from the index action in the Posts controller!';
        View::renderTemplate('Posts/index.html', [
            'posts' => $posts
        ]);
    }

    /**
     * Show the add new page
     *
     * @return void
     */
    public function addNewAction(): void
    {
        echo 'Hello from the addNew action in the Posts controller!';
    }

    /**
     * Show the edit page
     *
     * @return void
     */
    public function editAction()
    {
        echo 'Hello from the edit action in the Posts controller!';
        echo '<p>Route parameters: <pre>' .
            htmlspecialchars(print_r($this->route_params, true)) . '</pre></p>';
    }
}