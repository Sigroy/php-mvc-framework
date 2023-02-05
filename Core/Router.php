<?php

namespace MVCFramework\Core;

class Router
{
    /**
     * Associative array of routes (the routing table)
     * @var array
     */
    protected array $routes = [];

    /**
     * Parameters from the matched route
     * @var array
     */
    protected array $params = [];

    /**
     * Add a route to the routing table
     *
     * @param string $route The route URL
     * @param array $params Parameters (controller, action, etc.)
     *
     * @return void
     */
    public function add(string $route, array $params = []): void
    {
        // Wanted regexp = /^(?P<controller>[a-z-]+)\/(?P<action>[a-z-]+)$/
//        echo '<pre>';
//        var_dump($route);
//        echo '<br>';
//        echo '</pre>';

        // Convert the route to a regular expression: escape forward slashes
        $route = preg_replace('/\//', '\\/', $route);
        // Replacing / to \/

//        echo '<pre>';
//        var_dump($route);
//        echo '<br>';
//        echo '</pre>';

        // Convert variables e.g. {controller}
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);
        // Replacing {controller}/{index} to (?P<controller>[a-z-]+)\/(?P<action>[a-z-]+)
//echo 'Aquiiiiiiiiii: ' . $route;
//        echo '<pre>';
//        var_dump($route);
//        echo '<br>';
//        echo '</pre>';

        // Convert variables with custom regular expressions e.g. {id:\d+}
        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);

//        echo '<pre>';
//        var_dump($route);
//        echo '<br>';
//        echo '</pre>';

        // Add start and end delimiters, and case-insensitive flag
        $route = '/^' . $route . '$/i';

//        echo '<pre>';
//        var_dump($route);
//        echo '<br>';
//        echo '</pre>';

        $this->routes[$route] = $params;
    }
    /*
     array(3) {
  [""]=>
  array(2) {
    ["controller"]=>
    string(4) "Home"
    ["action"]=>
    string(5) "index"
  }
  ["posts"]=>
  array(2) {
    ["controller"]=>
    string(5) "Posts"
    ["action"]=>
    string(5) "index"
  }
  ["posts/new"]=>
  array(2) {
    ["controller"]=>
    string(5) "Posts"
    ["action"]=>
    string(3) "new"
  }
}
     */

    /**
     * Get all the routes from the routing table
     *
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * Match the route to the routes in the routing table, setting the $params
     * property if a route is found.
     *
     * @param string $url The route URL
     *
     * @return boolean true if a match found, false otherwise
     * @throws \Exception
     */
    public function match(string $url): bool
    {
//        foreach ($this->routes as $route => $params) {
//            if ($url === $route) {
//                $this->params = $params;
//                return true;
//            }
//        }
//        return false;

//        $reg_exp = "/^(?P<controller>[a-z-]+)\/(?P<action>[a-z-]+)$/";

//        if (preg_match($reg_exp, $url, $matches)) {
        // Get named capture group values
        $params = [];
        /*array(5) {
              [0]=>
              string(9) "posts/new"
              ["controller"]=>
              string(5) "posts"
              [1]=>
              string(5) "posts"
              ["action"]=>
              string(3) "new"
              [2]=>
              string(3) "new"
            }*/

//        var_dump($url);
//        echo '<br>';
        foreach ($this->routes as $route => $params) {
//            echo '<pre>';
//            var_dump($route);
//            echo '</pre>';

//            echo '<pre>';
//            var_dump($params);
//            echo '</pre>';
            if (preg_match($route, $url, $matches)) {
//                echo $route;
//                echo '<pre>';
//                var_dump($matches);
//                echo '</pre>';
                foreach ($matches as $key => $match) {
                    // is_string to only get the keys that aren't indexed key numbers.
                    if (is_string($key)) {
                        $params[$key] = $match;
                    }
                }

                $this->params = $params;

                if (preg_match('/action$/i', $this->params['action']) === 0) {
                    return true;
                } else {
                    throw new \Exception('Method ' . $this->params["action"] .
                        ' cannot be called directly - remove the Action suffix to call this method');
                }

//        }
            }
        }
        return false;
    }

    /**
     * Get the currently matched parameters
     *
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     *
     *
     * @param string $url The route URL
     *
     * @return void
     * @throws \Exception
     */
    public function dispatch(string $url): void
    {
        $url = $this->removeQueryStringVariables($url);

        if ($this->match($url)) {
            $controller = $this->params['controller'];
            $controller = $this->convertToStudlyCaps($controller);
//            $controller = "MVCFramework\\App\\Controllers\\$controller";
            $controller = $this->getNamespace() . $controller;
            if (class_exists($controller)) {
                $controller_object = new $controller($this->params);

                $action = $this->params['action'];
                $action = $this->convertToCamelCase($action);

                $controller_object->$action();

            } else {
                echo "Controller class $controller not found.";
            }
        } else {
            echo 'No route matched.';
        }
    }

    /**
     * Convert the string with hyphens to StudlyCaps
     * e.g. postauthors => PostAuthors
     *
     * @param string $string The string to convert
     *
     * @return string
     */
    protected function convertToStudlyCaps(string $string): string
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    /**
     * Convert the string with hyphens to camelCase,
     * e.g. add-new => addNew
     *
     * @param string $string The string to convert
     *
     * @return string
     */
    protected function convertToCamelCase(string $string): string
    {
        return lcfirst($this->convertToStudlyCaps($string));
    }

    /**
     * Remove the query string variables from the URL (if any). As the full
     * query string is used for the route, any variables at the end will need
     * to be removed before the route is matched to the routing table. For
     * example:
     *
     * URL                           $_SERVER['QUERY_STRING']  Route
     * --------------------------------------------------------------------
     * localhost                     ''                        ''
     * localhost/?                    ''                        ''
     * localhost/?page=1             page=1                    ''
     * localhost/posts?page=1        posts&page=1              posts
     * localhost/posts/index         posts/index               posts/index
     * localhost/posts/index?page=1  posts/index&page=1        posts/index
     *
     * A URL of the format localhost/?page (one variable, no value) won't
     * work however. (NB. The .htaccess file converts the first ? to a & when
     * it's passed through to the $_SERVER variable)
     *
     * @param string $url The full URL
     *
     * @return string The URL with the query string variables removed
     */
    protected function removeQueryStringVariables(string $url): string
    {
        if ($url === '') {
            return $url;
        }
        $parts = explode('&', $url, 2);

        return (!str_contains($parts[0], '=')) ? $parts[0] : '';

        // Before PHP 8: strpos($parts[0], '=') === false
        // /posts/index=1 for example shouldn't be allowed
        // so the user is sent back the main page "/"

    }

    /**
     * Get the namespace for the controller class. The namespace defined in the
     * parameters is added if present.
     *
     * @return string The request URL
     */
    protected function getNamespace(): string
    {
        $namespace = "MVCFramework\\App\\Controllers\\";

        if (array_key_exists('namespace', $this->params)) {
            $namespace .= $this->params['namespace'] . '\\';
        }
        return $namespace;
    }
}