<?php

namespace MVCFramework\App\Controllers;

class Home extends \MVCFramework\Core\Controller
{

    /**
     * Before filter
     *
     * @return bool
     */
    protected function before(): bool
    {
        echo "(before) ";
        return true;
    }

    /**
     * After filter
     *
     * @return void
     */
    protected function after(): void
    {
        echo " (after)";
    }

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        echo 'Hello from the index action in the Home controller';
    }
}