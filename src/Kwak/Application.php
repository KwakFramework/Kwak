<?php

namespace Kwak;

use Imbrix\DependencyManager;

use Kwak\Http\Controller\ControllerExecutor;
use Kwak\Http\Controller\ControllerMatcher;
use Kwak\Routing\RoutingDefinition;
use Kwak\Routing\RoutingMatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Application
 *
 * @package Kwak
 */
class Application
{
    /** @var DependencyManager */
    protected $dependencyManager;

    public function init()
    {
        $this->initDependencyManager();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function run()
    {
        try {
            $request = Request::createFromGlobals();

            $request = $this->dependencyManager->get('routingMatcher')->matchRequest($request);

            $controllerMatcher = $this->dependencyManager->get('controllerMatcher');
            $controllerMatch = $controllerMatcher->matchRoute($request);

            $controllerExecutor = new ControllerExecutor();

            return $controllerExecutor->executeCallable($controllerMatch->getControllerCallable(), $controllerMatch->getArguments());
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * @param Response $response
     */
    public function output(Response $response)
    {
        echo $response->getContent();
    }

    /**
     * @return DependencyManager
     */
    public function getDependencyManager()
    {
        return $this->dependencyManager;
    }

    /**
     * Initiate Imbrix DependencyManager
     */
    protected function initDependencyManager()
    {
        $dependencyManager = new DependencyManager();

        $dependencyManager->addService('dependencyManager', function() use ($dependencyManager) {
            return $dependencyManager;
        });

        $dependencyManager->addService('routing', function() {
            return new RoutingDefinition();
        });

        $dependencyManager->addService('routingMatcher', function ($routing) {
            return new RoutingMatcher($routing);
        });

        $dependencyManager->addService('application', function () {
            return $this;
        });

        $dependencyManager->addService('controllerMatcher', function ($dependencyManager) {
            return new ControllerMatcher($dependencyManager);
        });

        $this->dependencyManager = $dependencyManager;
    }

    protected function handleException(\Exception $e)
    {
        return new Response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
