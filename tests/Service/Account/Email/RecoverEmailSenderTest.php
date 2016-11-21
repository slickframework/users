<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Service\Account\Email;

use Slick\Users\Domain\Account;
use Slick\Users\Service\Account\Email\Message\RecoverPasswordEmail;
use Slick\Users\Service\Account\Email\RecoverEmailSender;
use Slick\Users\Service\Email\EmailTransportInterface;
use Slick\Users\Tests\TestCase;

/**
 * Recover Email Sender Test Case
 *
 * @package Slick\Users\Tests\Service\Account\Email
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class RecoverEmailSenderTest extends TestCase
{

    /**
     * @var RecoverEmailSender
     */
    protected $sender;

    protected function setUp()
    {
        parent::setUp();
        $this->sender = new RecoverEmailSender();
    }

    public function testGetEmailMessage()
    {
        $account = new Account();
        $message = $this->sender->setAccount($account)
            ->getRecoverPasswordEmail();
        $this->assertInstanceOf(RecoverPasswordEmail::class, $message);
    }

    public function testSendEmail()
    {
        $account = new Account();
        $message = \Phake::mock(
            RecoverPasswordEmail::class,
            $this->getSelfAnswer()
        );
        $transport = \Phake::mock(EmailTransportInterface::class);
        $this->sender
            ->setEmailTransport($transport)
            ->setRecoverPasswordEmail($message)
            ->sendTo($account)
        ;
        \Phake::verify($message)->prepareMessage();
        \Phake::verify($transport)->send($message);
    }
}
