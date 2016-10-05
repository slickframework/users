<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Service\Account;

use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_TestCase as TestCase;
use Slick\Users\Service\Account\PasswordEncryptionService;

/**
 * Password Encryption Service Test case
 *
 * @package Slick\Users\Tests\Service\Account
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class PasswordEncryptionServiceTest extends TestCase
{

    /**
     * Should use the php's password_hash function
     * @test
     */
    public function hashPassword()
    {
        $password = '123456';
        $service = new PasswordEncryptionService($password);
        $this->assertTrue(password_verify($password, (string) $service));
    }

    /**
     * Should use the php's password_verify function
     * @test
     */
    public function verifyPassword()
    {
        $password = '123456';

        $service = new PasswordEncryptionService($password);
        $hash = password_hash(
            $password,
            PASSWORD_BCRYPT,
            ['cost' => PasswordEncryptionService::COST]
        );
        $this->assertTrue($service->match($hash));
    }
}
