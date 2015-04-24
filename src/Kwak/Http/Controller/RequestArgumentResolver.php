<?php

namespace Kwak\Http\Controller;

use Imbrix\DependencyManager;

/**
 * Class RequestArgumentResolver
 *
 * @package Kwak\Http\Controller
 */
class RequestArgumentResolver implements ArgumentResolverInterface
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
     */
    public function resolveArgument(array $orderedArguments, \ReflectionParameter $argument)
    {
        $request = $this->dependencyManager->get('requestPool')->getLastRequest();

        // The asked parameter is the Request
        if ($argument->getClass() && $argument->getClass()->name == get_class($request)) {
            $orderedArguments[] = $request;
        }

        return $orderedArguments;
    }
}
