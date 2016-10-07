<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Service;

use Interop\Container\ContainerInterface;
use Monolog\Handler\HandlerInterface;
use PHPUnit_Framework_TestCase as TestCase;
use Psr\Log\LoggerInterface;
use Slick\Configuration\ConfigurationInterface;
use Slick\Users\Service\Logging;

/**
 * Logging Test Case
 *
 * @package Slick\Users\Tests\Service
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class LoggingTest extends TestCase
{

    public function testLogger()
    {
        $handler = \Phake::mock(HandlerInterface::class);
        $configuration = \Phake::mock(ConfigurationInterface::class);
        \Phake::when($configuration)
            ->get('logging.facility', 'slick-users')
            ->thenReturn('slick-users');
        $container = \Phake::mock(ContainerInterface::class);
        \Phake::when($container)
            ->get('defaultHandler')
            ->thenReturn($handler);
        $sut = new Logging();
        $logger = $sut->setContainer($container)
            ->setSettings($configuration)
            ->getLogger();
        $this->assertInstanceOf(LoggerInterface::class, $logger);
    }
}
