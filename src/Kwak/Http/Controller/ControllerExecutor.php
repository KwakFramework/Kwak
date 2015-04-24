<?php

namespace Kwak\Http\Controller;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class ControllerExecutor
 *
 * @package Kwak\Http\Controller
 */
class ControllerExecutor
{
    /**
     * @param callable $callable
     * @param array    $arguments
     *
     * @return Response
     * @throws \Exception
     */
    public function executeCallable(\Closure $callable, array $arguments)
    {
        $response = call_user_func_array($callable, [$arguments]);

        if (!$response instanceof Response) {
            throw new \Exception('Your controller should return a Response');
        }

        return $response;
    }
}
