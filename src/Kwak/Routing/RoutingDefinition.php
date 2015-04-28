<?php

namespace Kwak\Routing;

use Symfony\Component\HttpFoundation\Request;

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
    public function addPost($routeName, $routePath, $routeExecution)
    {
        $this->routes[] = new Route($routeName, $routePath, $routeExecution, Request::METHOD_POST);
    }

    /**
     * @param string $routeName
     * @param string $routePath
     * @param string $routeExecution
     */
    public function addGet($routeName, $routePath, $routeExecution)
    {
        $this->routes[] = new Route($routeName, $routePath, $routeExecution, Request::METHOD_GET);
    }

    /**
     * @param string $routeName
     * @param string $routePath
     * @param string $routeExecution
     */
    public function addDelete($routeName, $routePath, $routeExecution)
    {
        $this->routes[] = new Route($routeName, $routePath, $routeExecution, Request::METHOD_DELETE);
    }

    /**
     * @param string $routeName
     * @param string $routePath
     * @param string $routeExecution
     */
    public function addPut($routeName, $routePath, $routeExecution)
    {
        $this->routes[] = new Route($routeName, $routePath, $routeExecution, Request::METHOD_PUT);
    }
    /**
     * @param string $routeName
     * @param string $routePath
     * @param string $routeExecution
     */
    public function add($routeName, $routePath, $routeExecution)
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
