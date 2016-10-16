<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests;

use Interop\Container\ContainerInterface;
use PHPUnit_Framework_TestCase as PHPUnitTestCase;

/**
 * Test Case
 *
 * @package Slick\Users\Tests
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class TestCase extends PHPUnitTestCase
{

    /**
     * Get a container with provided dependencies
     *
     * @param array $dependencies
     *
     * @return mixed
     */
    protected function getContainerMock($dependencies = [])
    {
        $container = \Phake::mock(ContainerInterface::class);
        \Phake::when($container)
            ->get($this->isType('string'))
            ->thenReturnCallback(function ($key) use ($dependencies) {
                $element = null;
                if (array_key_exists($key, $dependencies)) {
                    $element = $dependencies[$key];
                }
                return $element;
            })
        ;
        return $container;
    }

}