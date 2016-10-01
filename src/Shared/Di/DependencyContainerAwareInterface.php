<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Shared\Di;

use Interop\Container\ContainerInterface;

/**
 * Dependency Injection Container Aware Interface
 *
 * @package Slick\Users\Shared\Di
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface DependencyContainerAwareInterface
{

    /**
     * Get dependency injection container
     *
     * @return ContainerInterface
     */
    public function getContainer();

    /**
     * Set container interface
     *
     * @param ContainerInterface $container
     *
     * @return self|$this
     */
    public function setContainer(ContainerInterface $container);
}
