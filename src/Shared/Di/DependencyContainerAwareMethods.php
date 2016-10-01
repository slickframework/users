<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Shared\Di;

use Interop\Container\ContainerInterface;
use Slick\Mvc\Application;

/**
 * Dependency Container Aware Methods
 *
 * This trait is an implementation for the DependencyContainerAwareInterface
 * interface that can be used in any class that need it.
 *
 * @package Slick\Users\Shared\Di
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
trait DependencyContainerAwareMethods
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Get dependency injection container
     *
     * @return ContainerInterface
     */
    public function getContainer()
    {
        if (!$this->container) {
            $this->setContainer(Application::container());
        }
        return $this->container;
    }

    /**
     * Set container interface
     *
     * @param ContainerInterface $container
     *
     * @return self|$this
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
        return $this;
    }
}
