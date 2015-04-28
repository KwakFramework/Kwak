<?php

namespace Kwak\Routing;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class RoutingMatcher
 *
 * @package Kwak\Routing
 */
class RoutingMatcher
{
    /** @var RoutingDefinition */
    protected $routingDefinition;

    /**
     * @param RoutingDefinition $routingDefinition
     */
    public function __construct(RoutingDefinition $routingDefinition)
    {
        $this->routingDefinition = $routingDefinition;
    }

    /**
     * @param Request $request
     *
     * @return Request
     * @throws \Exception
     */
    public function matchRequest(Request $request)
    {
        $pathInfo = $request->getPathInfo();

        foreach ($this->routingDefinition->getRoutes() as $route) {
            if ($route->getMethod() && $request->getMethod() != $route->getMethod()) {
                continue;
            }

            $routePath = $this->convertPathToRegex($route->getPath());

            if (preg_match($routePath, $pathInfo, $routeArgs)) {
                preg_match_all('/{([a-z0-9_]*)}/i', $route->getPath(), $matches);

                $parsedRouteArgs = [];

                for ($i = 0, $count = count($matches[1]); $i < $count; $i++) {
                    $parsedRouteArgs[$matches[1][$i]] = $routeArgs[$i + 1];
                }

                $request->attributes->set('route', $route);

                $request->attributes->set('route_args', $parsedRouteArgs);

                return $request;
            }
        }

        throw new \Exception('Route not found for current path');
    }

    /**
     * @param string $routePath
     *
     * @return string
     */
    protected function convertPathToRegex($routePath)
    {
        $routePath = str_replace('/', '\/', $routePath);
        $routePath = str_replace('.', '\.', $routePath);

        return '/' . preg_replace('/{[a-z0-9_]*}/i', "([a-z0-9-_]*)", $routePath) . '/i';
    }
}
