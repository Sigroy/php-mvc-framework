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

        // Convert the route to a regular expression: escape forward slashes
        $route = preg_replace('/\//', '\\/', $route);
        // Replacing / to \/

        // Convert variables e.g. {controller}
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);
        // Replacing {controller}/{index} to (?P<controller>[a-z-]+)\/(?P<action>[a-z-]+)

        // Convert variables with custom regular expressions e.g. {id:\d+}
        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);

        // Add start and end delimiters, and case-insensitive flag
        $route = '/^' . $route . '$/i';

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
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                foreach ($matches as $key => $match) {
                    // is_string to only get the keys that aren't indexed key numbers.
                    if (is_string($key)) {
                        $params[$key] = $match;
                    }
                }

                $this->params = $params;
                return true;
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
}