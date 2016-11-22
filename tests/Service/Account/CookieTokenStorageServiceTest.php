<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Service\Account;

use Slick\Users\Domain\Token;
use Slick\Users\Service\Account\CookieTokenStorageService;
use Slick\Users\Tests\TestCase;

include_once 'functions.php';

/**
 * Cookie Token Storage Service Test Case
 *
 * @package Slick\Users\Tests\Service\Account
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class CookieTokenStorageServiceTest extends TestCase
{
    public static $cookieData = [];

    /**
     * @var CookieTokenStorageService
     */
    protected $service;

    /**
     * Sets the SUT object
     */
    protected function setUp()
    {
        parent::setUp();
        $this->service = new CookieTokenStorageService();
    }

    public function testSetCookie()
    {
        $token = \Phake::mock(Token::class);
        \Phake::when($token)
            ->getPublicToken()
            ->thenReturn('a:token');
        $this->service->set('test', $token);

        $this->assertEquals(
            'a:token',
            CookieTokenStorageServiceTest::$cookieData['value']
        );
    }

    public function testEraseCookie()
    {
        $this->service->erase('test');
        $this->assertLessThan(time(), self::$cookieData['expire']);
    }
}


