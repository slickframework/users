<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Service\Account\Listener;

use Slick\Users\Domain\Account;
use Slick\Users\Service\Account\Email\AccountEmailSenderInterface;
use Slick\Users\Service\Account\Event\EmailChange;
use Slick\Users\Service\Account\Listener\EmailChanged;
use Slick\Users\Tests\TestCase;

/**
 * Email Changed Test Case
 *
 * @package Slick\Users\Tests\Service\Account\Listener
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class EmailChangedTest extends TestCase
{
    /**
     * @var EmailChanged
     */
    protected $listener;

    /**
     * Set the SUT listener Object
     */
    protected function setUp()
    {
        parent::setUp();
        $this->listener = new EmailChanged();
    }

    /**
     * Should check true if listener is itself
     */
    public function testIdentification()
    {
        $event = new EmailChanged();
        $this->assertTrue($event->isListener($event));
    }

    /**
     * Should lazy load the service from container
     * @test
     */
    public function testEmailSender()
    {
        $service = \Phake::mock(AccountEmailSenderInterface::class);
        $dep = ['confirmEmailSender' => $service];
        $this->listener->setContainer($this->getContainerMock($dep));
        $this->assertSame($service, $this->listener->getEmailSender());
    }

    /**
     * Should use the sendTo method from service
     * @test
     */
    public function testEventHandling()
    {
        $account = new Account();
        $event = new EmailChange($account);
        $service = \Phake::mock(AccountEmailSenderInterface::class);
        $this->listener->setEmailSender($service);
        $this->listener->handle($event);
        \Phake::verify($service)->sendTo($account);
    }
}
