<?php

/**
 * This file is part of Users.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Shared\Common;

use Psr\Log\LoggerInterface;
use Slick\Users\Shared\Common\LoggerAwareInterface;
use Slick\Users\Shared\Common\LoggerAwareMethods;
use Slick\Users\Shared\Di\DependencyContainerAwareMethods;
use Slick\Users\Tests\TestCase;

/**
 * Logger Aware Methods trait test case
 *
 * @package Slick\Users\Tests\Shared\Common
 * @author  Filipe Silva <filipe.silva@sata.pt>
 */
class LoggerAwareMethodsTest extends TestCase
{
    /** @var  LoggerAwareInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $trait;

    protected function setUp()
    {
        parent::setUp();
        $this->trait = $this->getMockForTrait(LoggerAwareMethods::class);
    }


    /**
     * Grab the logger form container
     */
    public function testLoggerLazyLoadFromContainer()
    {
        $logger = \Phake::mock(LoggerInterface::class);
        $container = $this->getContainerMock(
            ['logger' => $logger]
        );
        $this->trait->method('getContainer')->willReturn($container);
        $this->assertSame($logger, $this->trait->getLogger());
    }
}
