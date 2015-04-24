<?php

namespace Kwak\Http\Request;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class RequestPool
 *
 * @package Kwak\Http\Request
 */
class RequestPool
{
    /** @var Request[] */
    protected $requests = [];

    /**
     * @param Request $request
     */
    public function add(Request $request)
    {
        $this->requests[] = $request;
    }

    /**
     * @return mixed|null
     */
    public function pop()
    {
        if (count($this->requests) == 0) {
            return null;
        }

        return array_pop($this->requests);
    }

    /**
     * @return Request
     */
    public function getLastRequest()
    {
        if (count($this->requests) == 0) {
            return null;
        }

        return end($this->requests);
    }
}
