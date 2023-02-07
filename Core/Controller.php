<?php

namespace MVCFramework\Core;

abstract class Controller
{
    /**
     * Parameters from the matched route
     * @var array
     */
    protected array $route_params = [];

    /**
     * Class constructor
     *
     * @param array $route_params Parameters from the route
     *
     * @return void
     */
    public function __construct(array $route_params)
    {
        $this->route_params = $route_params;
    }

    /**
     *
     * @param string $name
     * @param array $arguments
     * @return void
     */
    public function __call(string $name, array $arguments): void
    {
        $method = $name . 'Action';

        if (method_exists($this, $method)) {
            if ($this->before() !== false) {
                call_user_func_array([$this, $method], $arguments);
                $this->after();
            }
        } else {
            echo 'Method ' . str_replace('Action', '', $method) . ' not found in controller ' . get_class($this);
        }
    }

    /**
     * Before filter - called before an action method.
     *
     * @return mixed
     */
    protected function before(): mixed
    {
        return null;
    }

    /**
     * After filter - called after an action method.
     *
     * @return void
     */
    protected function after(): void
    {

    }
}