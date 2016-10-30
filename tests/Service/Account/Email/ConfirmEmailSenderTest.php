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
use Slick\Users\Service\Account\Email\Message\EmailMessageInterface;
use Slick\Users\Service\Email\EmailTransportInterface;
use Slick\Users\Tests\TestCase;

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
        $transport = \Phake::mock(EmailTransportInterface::class);
        $this->sender->setEmailTransport($transport);
        $this->sender->sendTo(new Account());
        \Phake::verify($transport)->send($this->isInstanceOf(EmailMessageInterface::class));
    }
}

