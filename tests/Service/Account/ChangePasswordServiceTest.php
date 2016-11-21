<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Service\Account;

use League\Event\EmitterInterface;
use Psr\Log\LoggerInterface;
use Slick\Users\Domain\Account;
use Slick\Users\Domain\Credential;
use Slick\Users\Domain\Token;
use Slick\Users\Service\Account\ChangePasswordService;
use Slick\Users\Service\Account\Event\RecoveredPassword;
use Slick\Users\Tests\TestCase;

/**
 * Change Password Service Test Case
 *
 * @package Slick\Users\Tests\Service\Account
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class ChangePasswordServiceTest extends TestCase
{

    /**
     * @var ChangePasswordService
     */
    protected $service;

    protected $account;

    protected $credential;

    protected function setUp()
    {
        parent::setUp();
        $this->service = new ChangePasswordService(\Phake::mock(LoggerInterface::class));
    }

    public function testGetCredential()
    {
        $this->service->setToken($this->getTokenMock());
        $this->assertSame(
            $this->credential,
            $this->service->getCredential()
        );
    }

    /**
     * @expectedException \Slick\Users\Exception\Accounts\InvalidTokenException
     */
    public function testInvalidToken()
    {
        $token = $this->getTokenMock();
        \Phake::when($token)->isValid()->thenReturn(false);
        $this->service
            ->setToken($token)
            ->change('234567')
        ;
    }

    public function testChange()
    {
        $emitter = \Phake::mock(EmitterInterface::class);
        $token = $this->getTokenMock();
        \Phake::when($token)->isValid()->thenReturn(true);
        $this->service
            ->setEmitter($emitter)
            ->setToken($token)
            ->change('234567')
        ;

        \Phake::verify($emitter)->emit($this->isInstanceOf(RecoveredPassword::class));
    }

    /**
     * @return Token|\Phake_IMock|Token
     */
    protected function getTokenMock()
    {
        /** @var Token|\Phake_IMock $token */
        $token = \Phake::partialMock(Token::class);
        $token->account = $this->getAccount();
        returN $token;
    }

    /**
     * @return Account
     */
    public function getAccount()
    {
        if (!$this->account) {
            $account = new Account(['credential' => $this->getCredential()]);
            $this->account = $account;
        }
        return $this->account;
    }

    /**
     * @return Credential
     */
    public function getCredential()
    {
        if (!$this->credential) {
            $credential = \Phake::partialMock(Credential::class);
            \Phake::when($credential)->save()->thenReturn(1);
            $this->credential = $credential;
        }
        return $this->credential;
    }

}
