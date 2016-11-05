<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service\Account\Listener;

use League\Event\EventInterface;
use League\Event\ListenerInterface;
use Slick\Users\Service\Account\Email\AccountEmailSenderInterface;
use Slick\Users\Service\Account\Event\EmailChange;
use Slick\Users\Shared\Di\DependencyContainerAwareInterface;
use Slick\Users\Shared\Di\DependencyContainerAwareMethods;

/**
 * Email Changed listener
 *
 * @package Slick\Users\Service\Account\Listener
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class EmailChanged implements
    ListenerInterface,
    DependencyContainerAwareInterface
{

    /**
     * Methods to access dependency container
     */
    use DependencyContainerAwareMethods;

    /**
     * @var AccountEmailSenderInterface
     */
    protected $emailSender;

    /**
     * Handle an event.
     *
     * @param EventInterface|EmailChange $event
     *
     * @return void
     */
    public function handle(EventInterface $event)
    {
        $this->getEmailSender()->sendTo($event->getAccount());
    }

    /**
     * Check whether the listener is the given parameter.
     *
     * @param mixed $listener
     *
     * @return bool
     */
    public function isListener($listener)
    {
        return $listener === $this;
    }
    /**
     * Get E-mail sender
     *
     * @return AccountEmailSenderInterface
     */
    public function getEmailSender()
    {
        if (!$this->emailSender) {
            $this->setEmailSender(
                $this->getContainer()->get('confirmEmailSender')
            );
        }
        return $this->emailSender;
    }

    /**
     * Set e-mail sender
     *
     * @param AccountEmailSenderInterface $emailSender
     *
     * @return EmailChanged
     */
    public function setEmailSender(AccountEmailSenderInterface $emailSender)
    {
        $this->emailSender = $emailSender;
        return $this;
    }
}
