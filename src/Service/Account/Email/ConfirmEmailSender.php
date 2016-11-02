<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service\Account\Email;

use Slick\Users\Domain\Account;
use Slick\Users\Service\Account\Email\Message\ConfirmAccountEmail;
use Slick\Users\Service\Email\EmailTransportInterface;
use Slick\Users\Shared\Di\DependencyContainerAwareInterface;
use Slick\Users\Shared\Di\DependencyContainerAwareMethods;

/**
 * Confirm Email Sender
 *
 * @package Slick\Users\Service\Account\Email
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class ConfirmEmailSender implements
    AccountEmailSenderInterface,
    DependencyContainerAwareInterface
{

    /**
     * @var EmailTransportInterface
     */
    protected $emailTransport;

    /**
     * Used to access dependency container
     */
    use DependencyContainerAwareMethods;

    /**
     * Set email transport agent
     *
     * @param EmailTransportInterface $transport
     *
     * @return self|$this|AccountEmailSenderInterface
     */
    public function setEmailTransport(EmailTransportInterface $transport)
    {
        $this->emailTransport = $transport;
        return $this;
    }

    /**
     * Get email agent
     *
     * @return EmailTransportInterface
     */
    public function getEmailTransport()
    {
        if (!$this->emailTransport) {
            $this->setEmailTransport(
                $this->getContainer()->get('mailTransportAgent')
            );
        }
        return $this->emailTransport;
    }

    /**
     * Sends out the e-mail message
     *
     * This method should return a boolean true if the message was successfully
     * delivered to the email transport agent.
     *
     * @param Account $account
     *
     * @return boolean
     */
    public function sendTo(Account $account)
    {
        $this->getEmailTransport()->send(
            $this->getConfirmEmail($account)->prepareMessage()
        );
        return true;
    }

    /**
     * Get a confirmation e-mail message object
     *
     * @param Account $account
     *
     * @return ConfirmAccountEmail
     */
    public function getConfirmEmail(Account $account)
    {
        return new ConfirmAccountEmail($account);
    }

}
