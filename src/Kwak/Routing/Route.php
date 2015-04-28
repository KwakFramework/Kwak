<?php

namespace Kwak\Routing;

/**
 * Class Route
 *
 * @package Kwak\Routing
 */
class Route
{
    /** @var string */
    protected $name;

    /** @var string */
    protected $path;

    /** @var string */
    protected $execution;

    /** @var string */
    protected $method;

    /**
     * @param string $name
     * @param string $path
     * @param string $execution
     * @param string $method
     */
    public function __construct($name, $path, $execution, $method = null)
    {
        $this->name      = $name;
        $this->path      = $path;
        $this->execution = $execution;
        $this->method    = $method;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Route
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     *
     * @return Route
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return string
     */
    public function getExecution()
    {
        return $this->execution;
    }

    /**
     * @param string $execution
     *
     * @return Route
     */
    public function setExecution($execution)
    {
        $this->execution = $execution;

        return $this;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     *
     * @return Route
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }
}
