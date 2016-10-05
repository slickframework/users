<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Service\Account\Register;

use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_TestCase as TestCase;
use Slick\Users\Service\Account\PasswordEncryptionService;
use Slick\Users\Service\Account\Register\RegisterRequest;

/**
 * Register Request Test Case
 *
 * @package Slick\Users\Tests\Service\Account\Register
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class RegisterRequestTest extends TestCase
{

    /**
     * Password should be set as a PasswordEncryptionService object
     * @test
     */
    public function passwordIsAService()
    {
        $request = new RegisterRequest('jon.doe@example.com', '123456');
        $this->assertInstanceOf(
            PasswordEncryptionService::class,
            $request->password
        );
    }
}
