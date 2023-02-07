<?php

namespace MVCFramework\Core;

class View
{
    /**
     * Render a view file
     *
     * @param string $view The view file
     * @param array $data
     * @return void
     */
    public static function render(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        $file = "../App/Views/$view"; // relative to Core directory

        if (is_readable($file)) {
            require $file;
        } else {
            echo "$file not found";
        }
    }

    /**
     * Render a view template using Twig
     *
     * @param string $template The template file
     * @param array $data Associative array of data to display in the view (optional)
     *
     * @return void
     */
    public static function renderTemplate(string $template, array $data = []): void
    {
        static $twig = null;

        if ($twig === null) {
            $loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__) . '/App/Views');
            $twig = new \Twig\Environment($loader);
        }

        echo $twig->render($template, $data);
    }

}