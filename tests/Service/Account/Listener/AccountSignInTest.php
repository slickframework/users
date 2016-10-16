<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Service\Account\Listener;

use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_TestCase as TestCase;
use Slick\Users\Service\Account\Listener\AccountSignIn;

/**
 * Account Sign In listener test case
 *
 * @package Slick\Users\Tests\Service\Account\Listener
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class AccountSignInTest extends TestCase
{

    public function testIdentification()
    {
        $listener = new AccountSignIn();
        $this->assertTrue($listener->isListener($listener));
    }
}
