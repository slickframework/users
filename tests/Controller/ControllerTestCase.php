<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Controller;

use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_TestCase as TestCase;
use Slick\Mvc\Controller;

/**
 * ControllerTestCase
 *
 * @package Slick\Users\Tests\Controller
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class ControllerTestCase extends TestCase
{

    /**
     * @var Controller
     */
    protected $controller;

    /**
     * Asserts controller's view variables array is empty
     *
     * @param string $message
     */
    public function assertEmptyVars($message = '')
    {
        $this->assertEmpty($this->controller->getViewVars(), $message);
    }

    /**
     * Get a mock object for provided class name
     *
     * @param $className
     *
     * @return MockObject
     */
    protected function getMockedObject($className)
    {
        $methods = get_class_methods($className);
        $object = $this->getMockBuilder($className)
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();
        return $object;
    }
}
