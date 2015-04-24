<?php

namespace Kwak\Http\Controller;

use Imbrix\DependencyManager;

/**
 * Class RouteArgumentResolver
 *
 * @package Kwak\Http\Controller
 */
class RouteArgumentResolver implements ArgumentResolverInterface
{
    /** @var DependencyManager */
    protected $dependencyManager;

    /**
     * @param DependencyManager $dependencyManager
     */
    public function __construct(DependencyManager $dependencyManager)
    {
        $this->dependencyManager = $dependencyManager;
    }

    /**
     * @param array                $orderedArguments
     * @param \ReflectionParameter $argument
     *
     * @return array
     * @throws \Exception
     */
    public function resolveArgument(array $orderedArguments, \ReflectionParameter $argument)
    {
        $request = $this->dependencyManager->get('requestPool')->getLastRequest();
        $routeArgs = $request->attributes->get('route_args');

        // Check if the route arguments contains the action parameter
        if (!isset($routeArgs[$argument->getName()])) {
            throw new \Exception(sprintf('%s parameter required for method', $argument->getName()));
        }

        $orderedArguments[] = $routeArgs[$argument->getName()];

        return $orderedArguments;
    }
}
