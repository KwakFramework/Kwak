<?php

namespace Kwak\Http\Request;

use Symfony\Component\HttpFoundation\Request;

class RequestPool
{
    /** @var Request[] */
    protected $requests;

    /**
     * @param Request $request
     */
    public function addRequest(Request $request)
    {
        $this->requests[] = $request;
    }

    /**
     * @return Request
     */
    public function getLastRequest()
    {
        return end($this->requests);
    }
}
