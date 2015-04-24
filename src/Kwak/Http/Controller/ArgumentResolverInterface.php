<?php

namespace Kwak\Http\Controller;

/**
 * Interface ArgumentResolverInterface
 *
 * @package Kwak\Http\Controller
 */
interface ArgumentResolverInterface
{
    /**
     * @param array                $orderedArguments
     * @param \ReflectionParameter $argument
     *
     * @return array
     */
    public function resolveArgument(array $orderedArguments, \ReflectionParameter $argument);
}
