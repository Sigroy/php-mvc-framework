<?php

namespace MyImplementation\Core;

class Router
{
    protected array $routes = [];
    protected array $params = [];

    public function add(string $route): void
    {
        $route = preg_replace('/\//', '\\/', $route);
        $route = preg_replace('/\{([a-z-]+)\}/', '(?P<\1>[a-z-]+)', $route);
        $route = preg_replace('/\{([a-z-]+):([^\}]+)\}/', '(?P<\1>\2)', $route);
        // (?P<controller>[a-z]+)/(?P<id>\d+)/(?P<action>[a-z]+)
        $route = '/^' . $route . '$/i';
        // /^(?P<controller>[a-z]+)/(?P<id>\d+)/(?P<action>[a-z]+)$/i
        $this->routes[] = $route;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    protected function match(string $url): bool
    {
        foreach ($this->routes as $route) {
            if (preg_match($route, $url, $matches)) {
                foreach ($matches as $key => $param) {
                    if (is_string($key)) {
                        $this->params[$key] = $param;
                    }
                }
                return true;
            }
        }
        return false;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function dispatch(string $url): void
    {
        if ($this->match($url)) {
            $controller = $this->convertToStudlyCaps($this->params['controller']);

            if (class_exists($controller)) {
                $controller_object = new $controller();

                $action = $this->convertToCamelCase($this->params['action']);
                if (is_callable([$controller_object, $action])) {
                    $controller_object->$action();
                } else {
                    echo "Method $action (in controller $controller) not found.";
                }
            } else {
                echo "Controller class $controller not found.";
            }
        } else {
            echo 'No match in the route.';
        }
    }

    protected function convertToStudlyCaps(string $string): string
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    protected function convertToCamelCase(string $string): string
    {
        return lcfirst($this->convertToStudlyCaps($string));
    }
}