<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests;

use PHPUnit_Framework_MockObject_MockObject as MockObject;

trait MockMethods
{

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