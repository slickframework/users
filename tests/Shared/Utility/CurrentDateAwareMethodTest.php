<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Shared\Utility;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Users\Shared\DataType\DateTime;
use Slick\Users\Shared\Utility\CurrentDateAwareMethods;

/**
 * Current Date Aware Method Test
 *
 * @package Slick\Users\Tests\Shared\Utility
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class CurrentDateAwareMethodTest extends TestCase
{

    /**
     * Should create a datetime object for current system datetime
     * @test
     */
    public function getCurrentDate()
    {
        /** @var CurrentDateAwareMethods $trait */
        $trait = $this->getMockForTrait(CurrentDateAwareMethods::class);
        $date = $trait->getCurrentDate();
        $this->assertInstanceOf(DateTime::class, $date);
    }
}
