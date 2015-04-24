<?php

namespace Kwak\Http\Controller;

use Imbrix\DependencyManager;

/**
 * Class ArgumentResolver
 *
 * @package Kwak\Http\Controller
 */
class ArgumentResolver implements ArgumentResolverInterface
{
    /**
     * @var ArgumentResolverInterface[]
     */
    protected $resolvers = [];

    /**
     * @param DependencyManager $dependencyManager
     * @param array             $resolvers
     */
    public function __construct(DependencyManager $dependencyManager, array $resolvers = [])
    {
        $this->resolvers = [
            new RequestArgumentResolver($dependencyManager),
            new RouteArgumentResolver($dependencyManager)
        ];

        $this->resolvers = array_merge($this->resolvers, $resolvers);
    }

    /**
     * @param                      $orderedArguments
     * @param \ReflectionParameter $argument
     *
     * @return array
     */
    public function resolveArgument(array $orderedArguments, \ReflectionParameter $argument)
    {
        $countOrderedArguments = count($orderedArguments);

        foreach ($this->resolvers as $resolver) {
            $orderedArguments = $resolver->resolveArgument($orderedArguments, $argument);

            if (count($orderedArguments) > $countOrderedArguments) {
                return $orderedArguments;
            }
        }

        return $orderedArguments;
    }
}
