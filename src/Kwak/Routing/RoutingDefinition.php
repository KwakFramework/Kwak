<?php

namespace Kwak\Routing;

/**
 * Class RoutingDefinition
 *
 * @package Kwak\Routing
 */
class RoutingDefinition
{
    /** @var Route[] */
    protected $routes;

    /**
     * @param string $routeName
     * @param string $routePath
     * @param string $routeExecution
     */
    public function addRoute($routeName, $routePath, $routeExecution)
    {
        $this->routes[] = new Route($routeName, $routePath, $routeExecution);
    }

    /**
     * @return Route[]
     */
    public function getRoutes()
    {
        return $this->routes;
    }
}
