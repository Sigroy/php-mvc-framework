<?php

namespace MVCFramework\App\Controllers\Admin;

class Users extends \MVCFramework\Core\Controller
{

    /**
     * Before filter
     *
     * @return bool
     */
    protected function before(): bool
    {
        // Make sure an admin user is logged in for example
        return true;
    }

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction(): void
    {
        echo 'User admin index';
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
}