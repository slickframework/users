<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Service\Account\Email;

use Slick\Users\Domain\Account;
use Slick\Users\Service\Account\Email\ConfirmEmailSender;
use Slick\Users\Service\Account\Email\Message\ConfirmAccountEmail;
use Slick\Users\Service\Account\Email\Message\EmailMessageInterface;
use Slick\Users\Service\Email\EmailTransportInterface;
use Slick\Users\Tests\TestCase;

/**
 * Confirm Email Sender Test Case
 *
 * @package Slick\Users\Tests\Service\Account\Email
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class ConfirmEmailSenderTest extends TestCase
{

    /**
     * @var ConfirmEmailSender
     */
    protected $sender;

    protected function setUp()
    {
        parent::setUp();
        $this->sender = new ConfirmEmailSender();
        $this->sender->setContainer(
            $this->getContainerMock(
                [
                    'mailTransportAgent' =>
                        \Phake::mock(EmailTransportInterface::class)
                ]
            )
        );
    }

    public function testGetTransporter()
    {
        $trp = $this->sender->getEmailTransport();
        $this->assertInstanceOf(EmailTransportInterface::class, $trp);
    }

    public function testSendMessage()
    {
        $account = new Account();
        $email = \Phake::mock(EmailMessageInterface::class);
        \Phake::when($email)->prepareMessage()->thenReturn($email);
        /** @var ConfirmEmailSender|\Phake_IMock $sender */
        $sender = \Phake::partialMock(ConfirmEmailSender::class);
        \Phake::when($sender)->getConfirmEmail($account)->thenReturn($email);
        $transport = \Phake::mock(EmailTransportInterface::class);
        $sender->setEmailTransport($transport);
        $sender->sendTo($account);
        \Phake::verify($transport)->send($this->isInstanceOf(EmailMessageInterface::class));
    }

    public function testGetConformEmail()
    {
        $email = $this->sender->getConfirmEmail(new Account());
        $this->assertInstanceOf(ConfirmAccountEmail::class, $email);
    }
}

