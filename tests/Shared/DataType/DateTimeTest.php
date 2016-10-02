<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Shared\DataType;

use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_TestCase as TestCase;
use Slick\Users\Shared\DataType\DateTime;

/**
 * Date Time Test Case
 *
 * @package Slick\Users\Tests\Shared\DataType
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class DateTimeTest extends TestCase
{

    /**
     * Should create current date
     * @test
     */
    public function createWithoutArguments()
    {
        $date = new DateTime();
        $this->assertInstanceOf(\DateTime::class, $date);
    }

    /**
     * Should output a DB friendly sting in output of __toString() method
     * @test
     */
    public function toStringIsDbFriendly()
    {
        $dateStr = '2016-10-02 00:14:34';
        $date = new DateTime($dateStr);
        $this->assertEquals($dateStr, $date);
    }

    /**
     * Should use the value of passed dateTime object to set ist internal date
     * @test
     */
    public function createFromOtherDateTime()
    {
        $dateStr = '2016-10-02 00:14:34';
        $date = new DateTime(
            new \DateTime($dateStr, new \DateTimeZone('UTC'))
        );
        $this->assertEquals($dateStr, $date);
    }

    /**
     * Should format timestamps properly to be used by PHP's
     * DateTime::__construct() method
     *
     * @test
     */
    public function createFromTimeStamp()
    {
        $time = time();
        $date = new DateTime($time);
        $this->assertInstanceOf(DateTime::class, $date);
    }
}
