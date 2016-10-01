<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests;

use Aura\Router\RouterContainer;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_TestCase as TestCase;
use Slick\Mvc\Application;
use Slick\Users\Bootstrap;

class BootstrapTest extends TestCase
{

    /**
     * @var Bootstrap
     */
    protected $appBootstrap;

    /**
     * Set the SUT bootstrap object
     */
    protected function setUp()
    {
        parent::setUp();
        /** @var Application|MockObject $app */
        $app = $this->getMockFor(Application::class);
        $this->appBootstrap = Bootstrap::initialise($app);
    }

    /**
     * Should load the router container from router middleware
     * @test
     */
    public function getRouteContainer()
    {
        $container = $this->appBootstrap->getRouterContainer();
        $this->assertInstanceOf(RouterContainer::class, $container);
    }

    /**
     * Should set the routes for user crud
     * @test
     */
    public function setUserRoutes()
    {
        $routerContainer = new RouterContainer();
        $this->appBootstrap
            ->setRouterContainer($routerContainer)
            ->addRoutes();
        $route = $routerContainer->getMap()->getRoute('user.browse');
        $this->assertEquals('users', $route->path);
    }

    /**
     * Get a mock object for provided class name
     *
     * @param string $class
     *
     * @return MockObject
     */
    protected function getMockFor($class)
    {
        $methods = get_class_methods($class);
        $mock = $this->getMockBuilder($class)
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();
        return $mock;
    }
}
