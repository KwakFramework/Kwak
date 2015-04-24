<?php

namespace Kwak;

use Imbrix\DependencyManager;
use Kwak\Http\Controller\ControllerExecutor;
use Kwak\Http\Controller\ControllerMatcher;
use Kwak\Http\Request\RequestPool;
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

        $this->initRequest();
    }

    /**
     * @return Response
     * @throws \Exception
     */
    public function run()
    {
        try {
            $request = $this->getDependencyManager()->get('requestPool')->getLastRequest();

            $this->dependencyManager->get('routingMatcher')->matchRequest($request);

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

        $dependencyManager->addService('application', function () {
            return $this;
        });

        $dependencyManager->addService('routingDefinition', function() {
            return new RoutingDefinition();
        });

        $dependencyManager->addService('routingMatcher', function ($routingDefinition) {
            return new RoutingMatcher($routingDefinition);
        });

        $dependencyManager->addService('controllerMatcher', function ($dependencyManager) {
            return new ControllerMatcher($dependencyManager);
        });

        $dependencyManager->addService('requestPool', function() {
            return new RequestPool();
        });

        $this->dependencyManager = $dependencyManager;
    }

    /**
     * Adds the current Request to the RequestPool
     */
    protected function initRequest()
    {
        $this->dependencyManager->get('requestPool')->add(Request::createFromGlobals());
    }

    /**
     * @param \Exception $e
     *
     * @return Response
     */
    protected function handleException(\Exception $e)
    {
        return new Response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
