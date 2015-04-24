<?php

namespace Kwak\Http\Controller;

use Kwak\Application;
use Kwak\Framework\Controller\BaseController;
use Imbrix\DependencyManager;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ControllerMatcher
 *
 * @package Kwak\Http\Controller
 */
class ControllerMatcher
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
     * @param Request $request
     *
     * @return ControllerMatch
     * @throws \Exception
     */
    public function matchRoute(Request $request)
    {
        if (strpos(':', $request->attributes->get('route')->getExecution()) !== false) {
            throw new \Exception(sprintf('The route %s has an invalid execution format', $request->attributes->get('route')->getName()));
        }

        $controllerInformations = explode(':', $request->attributes->get('route')->getExecution());

        if (count($controllerInformations) != 2) {
            throw new \Exception(sprintf('The route %s has an invalid execution format', $request->attributes->get('route')->getName()));
        }

        $controller = $controllerInformations[0];
        $method = $controllerInformations[1];

        if (!class_exists($controller)) {
            throw new \Exception(sprintf('Controller %s not found', $controller));
        }
        if (!is_subclass_of($controller, 'Kwak\Framework\Controller\BaseController')) {
            throw new \Exception(sprintf('Controller %s should extend from BaseController', $controller));
        }

        $this->matchMethod($controller, $method);

        $controllerMatch = new ControllerMatch();
        $dependencyManager = $this->dependencyManager;

        $controllerMatch->setControllerCallable(function($arguments) use ($dependencyManager, $controller, $method) {
            /** @var BaseController $controller */
            $controller = new $controller();

            $controller->setDependencyManager($dependencyManager);

            return call_user_func_array([$controller, $method], $arguments);
        });

        $controllerMatch->setArguments($this->getArguments($controller, $method));

        return $controllerMatch;
    }

    /**
     * @param string $controller
     * @param string $method
     *
     * @throws \Exception
     */
    protected function matchMethod($controller, $method)
    {
        if (!method_exists($controller, $method)) {
            throw new \Exception(sprintf('Controller %s does not provide any %s method', $controller, $method));
        }
    }

    /**
     * @param string  $controller
     * @param string  $method
     *
     * @return array
     * @throws \Exception
     */
    protected function getArguments($controller, $method)
    {
        // Build a ReflectionMethod to access controller action arguments
        $reflectedMethod = new \ReflectionMethod($controller, $method);
        $params = $reflectedMethod->getParameters();

        $argumentResolver = $this->dependencyManager->get('argumentResolver');

        $orderedArguments = [];

        foreach ($params as $param) {
            $orderedArguments = $argumentResolver->resolveArgument($orderedArguments, $param);
        }

        return $orderedArguments;
    }
}
