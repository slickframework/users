<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Shared\Filter;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Filter\StaticFilter;
use Slick\Users\Shared\Filter\Email;

/**
 * Email Test Case
 *
 * @package Slick\Users\Tests\Shared\Filter
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class EmailTest extends TestCase
{

    /**
     * Should use the PHP's filter_var with FILTER_SANITIZE_EMAIL
     * to sanitize e-mail address
     */
    public function testFilterEmail()
    {
        $email = ' jon.doe@example.com';
        $expected = 'jon.doe@example.com';
        $this->assertEquals(
            $expected,
            StaticFilter::filter(Email::class, $email)
        );
    }
}
