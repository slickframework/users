<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Service\Account;

use Slick\Users\Domain\Account;
use Slick\Users\Domain\Repository\AccountsRepositoryInterface;
use Slick\Users\Service\Account\Email\RecoverEmailSender;
use Slick\Users\Service\Account\RecoverPasswordService;
use Slick\Users\Tests\TestCase;

/**
 * Recover Password Service Test Case
 *
 * @package Slick\Users\Tests\Service\Account
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class RecoverPasswordServiceTest extends TestCase
{
    /**
     * @var RecoverPasswordService
     */
    protected $service;

    /**
     * Sets the SUT service object
     */
    protected function setUp()
    {
        parent::setUp();
        $this->service = new RecoverPasswordService();
    }

    /**
     * Lazy loads repository for Account entity from Orm factory
     * @test
     */
    public function testGetAccountsRepository()
    {
        $repo = $this->service->getAccountRepository();
        $this->assertInstanceOf(AccountsRepositoryInterface::class, $repo);
    }

    public function testGetAccount()
    {
        $email = 'jon.doe@example.com';
        $account = new Account(['email' => $email]);
        $repo = \Phake::mock(AccountsRepositoryInterface::class);
        \Phake::when($repo)->getByEmail($email)->thenReturn($account);
        $this->service->setEmail($email)->setAccountRepository($repo);
        $this->assertSame($account, $this->service->getAccount());
    }

    public function testGetRecoverEmailSender()
    {
        $service = \Phake::mock(RecoverEmailSender::class);
        $dep = ['recoverEmailSender' => $service];
        $this->service->setContainer($this->getContainerMock($dep));
        $this->assertSame($service, $this->service->getRecoverEmailSender());
    }

    public function testRequestEmail()
    {
        $account = new Account();
        $sender = \Phake::mock(RecoverEmailSender::class);
        $this->service
            ->setRecoverEmailSender($sender)
            ->setAccount($account)
            ->requestEmail();
        \Phake::verify($sender)->sendTo($account);
    }

    /**
     * @expectedException \Slick\Users\Exception\Accounts\UnknownEmailException
     */
    public function testSendToInvalidAccount()
    {
        $email = 'jon.doe@example.com';
        $repo = \Phake::mock(AccountsRepositoryInterface::class);
        \Phake::when($repo)->getByEmail($email)->thenReturn(null);
        $this->service
            ->setEmail($email)
            ->setAccountRepository($repo)
            ->requestEmail();
    }
}
