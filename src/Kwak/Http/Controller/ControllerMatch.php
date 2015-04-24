<?php

namespace Kwak\Http\Controller;

/**
 * Class ControllerMatch
 *
 * @package Kwak\Http\Controller
 */
class ControllerMatch
{
    /** @var \Closure */
    protected $controllerCallable;

    /** @var array */
    protected $arguments;

    /**
     * @return \Closure
     */
    public function getControllerCallable()
    {
        return $this->controllerCallable;
    }

    /**
     * @param \Closure $controllerCallable
     *
     * @return ControllerMatch
     */
    public function setControllerCallable(\Closure $controllerCallable)
    {
        $this->controllerCallable = $controllerCallable;

        return $this;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @param array $arguments
     *
     * @return ControllerMatch
     */
    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;

        return $this;
    }

    /**
     * @param array $argument
     *
     * @return ControllerMatch
     */
    public function addArgument($argument)
    {
        $this->arguments[] = $argument;

        return $this;
    }
}
