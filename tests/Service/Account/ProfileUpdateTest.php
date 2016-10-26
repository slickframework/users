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
use Slick\Users\Service\Account\Event\EmailChange;
use Slick\Users\Service\Account\ProfileUpdater;
use Slick\Users\Tests\TestCase;

/**
 * Profile Update Test Case
 *
 * @package Slick\Users\Tests\Service\Account
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class ProfileUpdateTest extends TestCase
{
    /**
     * @var ProfileUpdater
     */
    protected $service;

    /**
     * Set the SUT profile update service
     */
    protected function setUp()
    {
        parent::setUp();
        $logger = \Phake::mock(LoggerInterface::class);
        $this->service = new ProfileUpdater($logger);
    }

    /**
     * Should run the account save method
     * @test
     */
    public function updateAnAccount()
    {
        $account = \Phake::partialMock(Account::class,
            [
                'email' => 'test@example.com',
                'credential' => new Credential(
                    [
                        'email' => 'test@example.com'
                    ]
                )
            ]
        );
        \Phake::when($account)->save()->thenReturn(true);
        $this->assertSame(
            $this->service,
            $this->service->update($account)
        );
        \Phake::verify($account)->save();
    }

    /**
     * Should save the changes email also in the credential entity
     * @test
     */
    public function updateEmailChange()
    {
        list($account, $credential) = $this->getMissMatchData();
        $this->service->update($account);
        \Phake::verify($credential)->save();
        $this->assertFalse($account->isConfirmed());
    }

    /**
     * Should emit email change class whenever the account's e-mail changes
     * @test
     */
    public function testChangeEventEmitter()
    {
        $emitter = \Phake::mock(EmitterInterface::class);
        $this->service->setEmitter($emitter);
        $this->service->update($this->getMissMatchData()[0]);
        \Phake::verify($emitter)->emit($this->isInstanceOf(EmailChange::class));
    }

    /**
     * Get data for credential save tests
     *
     * @return array
     */
    protected function getMissMatchData()
    {
        $credential = \Phake::partialMock(
            Credential::class,['email' => 'test@example.org']
        );
        /** @var Account|\Phake_IMock $account */
        $account = \Phake::partialMock(Account::class,
            [
                'email' => 'test@example.com',
                'credential' => $credential
            ]
        );
        \Phake::when($account)->save()->thenReturn(true);
        \Phake::when($credential)->save()->thenReturn(true);
        return [$account, $credential];
    }
}
