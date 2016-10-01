<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Shared\Di;

use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_TestCase as TestCase;
use Slick\Mvc\Application;
use Slick\Users\Shared\Di\DependencyContainerAwareMethods;

/**
 * Dependency Container Aware Methods Trait Test Case
 *
 * @package Slick\Users\Tests\Shared\Di
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class DependencyContainerAwareMethodsTraitTest extends TestCase
{

    /**
     * Get the trait
     */
    use DependencyContainerAwareMethods;

    /**
     * Should get the container from the application
     * @test
     */
    public function getDependencyContainer()
    {
        $container = Application::container();
        $this->assertSame($container, $this->getContainer());
    }
}
