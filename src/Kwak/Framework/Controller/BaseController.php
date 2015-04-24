<?php

namespace Kwak\Framework\Controller;

use Imbrix\DependencyManager;

/**
 * Class BaseController
 *
 * @package Kwak\Framework\Controller
 */
abstract class BaseController
{
    /** @var DependencyManager */
    protected $dependencyManager;

    /**
     * @param DependencyManager $dependencyManager
     */
    public function setDependencyManager(DependencyManager $dependencyManager)
    {
        $this->dependencyManager = $dependencyManager;
    }
}
