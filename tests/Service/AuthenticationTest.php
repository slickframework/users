<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Service;

use Slick\Http\SessionDriverInterface;
use Slick\Users\Domain\Account;
use Slick\Users\Service\Account\Authentication as SignInService;
use Slick\Users\Service\Authentication;
use Slick\Users\Tests\TestCase;

/**
 * AuthenticationTest
 *
 * @package Slick\Users\Tests\Service
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class AuthenticationTest extends TestCase
{

    /**
     * @var Authentication
     */
    protected $auth;

    /**
     * Sets the SUT authentication object
     */
    protected function setUp()
    {
        parent::setUp();
        $this->auth = new Authentication();
    }

    /**
     * Should check the session signed in data to determine the current account
     * data. No data means its a guest account.
     * @test
     */
    public function testGetGuestAccess()
    {
        $session = \Phake::mock(SessionDriverInterface::class);
        \Phake::when($session)
            ->get(
                SignInService::SESSION_KEY,
                $this->isInstanceOf(SignInService\SignedInAccount::class)
            )
            ->thenReturnCallback(function ($key, $default){
                return $default;
            })
        ;
        $this->auth->setSession($session);
        $this->assertTrue($this->auth->isGuest());
        $this->assertInstanceOf(
            Account::class,
            $this->auth->getCurrentAccount()
        );
    }

    /**
     * Should check the session signed in data to determine the current account
     * data.
     * @test
     */
    public function testAccountAccess()
    {
        $account = new Account(['id' => '1231', 'name' => 'Jon Doe']);
        $signedInData = new SignInService\SignedInAccount(
            ['account' => $account]
        );
        $session = \Phake::mock(SessionDriverInterface::class);
        \Phake::when($session)
            ->get(
                SignInService::SESSION_KEY,
                $this->isInstanceOf(SignInService\SignedInAccount::class)
            )
            ->thenReturn($signedInData)
        ;
        $this->auth->setSession($session);
        $this->assertFalse($this->auth->isGuest());
        $this->assertSame($account, $this->auth->getCurrentAccount());
    }
}
