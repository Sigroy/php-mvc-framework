<?php

namespace MVCFramework\App\Controllers;

use MVCFramework\Core\View;

class Home extends \MVCFramework\Core\Controller
{

    /**
     * Before filter
     *
     * @return bool
     */
    protected function before(): bool
    {
//        echo "(before) ";
        return true;
    }

    /**
     * After filter
     *
     * @return void
     */
    protected function after(): void
    {
//        echo " (after)";
    }

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
//        echo 'Hello from the index action in the Home controller';
        /*View::render('Home/index.html', [
            'name' => 'Dave',
            'colours' => ['red', 'green', 'blue']
        ]);*/
        View::renderTemplate('Home/index.html', [
            'name' => 'Dave',
            'colours' => ['red', 'green', 'blue'],
        ]);
    }
}