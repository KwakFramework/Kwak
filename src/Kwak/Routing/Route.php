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

    /**
     * @param string $name
     * @param string $path
     * @param string $execution
     */
    public function __construct($name, $path, $execution)
    {
        $this->name      = $name;
        $this->path      = $path;
        $this->execution = $execution;
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
}
