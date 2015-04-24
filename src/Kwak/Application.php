<?php

namespace Kwak;

use Imbrix\DependencyManager;
use Kwak\Http\Controller\ArgumentResolver;
use Kwak\Http\Controller\ControllerExecutor;
use Kwak\Http\Controller\ControllerMatcher;
use Kwak\Http\Request\RequestPool;
use Kwak\Routing\RoutingDefinition;
use Kwak\Routing\RoutingMatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Class Application
 *
 * @package Kwak
 */
class Application implements HttpKernelInterface
{
    /** @var DependencyManager */
    protected $dependencyManager;

    /**
     * Initiate :
     * - DependencyManager and default dependencies
     * - Request
     */
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
        $request = $this->getDependencyManager()->get('requestPool')->getLastRequest();

        return $this->handle($request);
    }

    /**
     * Handle Request (conform to HttpKernelInterface)
     *
     * @param Request $request
     * @param int     $type
     * @param bool    $catch
     *
     * @return Response
     * @throws \Exception
     */
    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        try {
            $this->dependencyManager->get('routingMatcher')->matchRequest($request);

            $controllerMatcher = $this->dependencyManager->get('controllerMatcher');
            $controllerMatch = $controllerMatcher->matchRoute($request);

            $controllerExecutor = new ControllerExecutor();

            return $controllerExecutor->executeCallable($controllerMatch->getControllerCallable(), $controllerMatch->getArguments());
        } catch (\Exception $e) {
            if ($catch) {
                return $this->handleException($e);
            } else {
                throw $e;
            }
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

        $dependencyManager->addService('argumentResolver', function ($dependencyManager) {
            return new ArgumentResolver($dependencyManager);
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
