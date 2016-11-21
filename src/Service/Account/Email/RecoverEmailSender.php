<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service\Account\Email;

use Slick\Users\Domain\Account;
use Slick\Users\Service\Account\Email\Message\RecoverPasswordEmail;

/**
 * Recover Email Sender
 *
 * @package Slick\Users\Service\Account\Email
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class RecoverEmailSender extends ConfirmEmailSender
{

    /**
     * @var RecoverPasswordEmail
     */
    protected $recoverPasswordEmail;

    /**
     * @var Account
     */
    protected $account;

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
        $this->setAccount($account);
        $message = $this
            ->getRecoverPasswordEmail()
            ->prepareMessage();

        $this->getEmailTransport()->send($message);
        return true;
    }

    /**
     * Set account for e-mail
     *
     * @param Account $account
     *
     * @return RecoverEmailSender
     */
    public function setAccount(Account $account)
    {
        $this->account = $account;
        return $this;
    }

    /**
     * Get recover e-mail message
     *
     * @return RecoverPasswordEmail
     */
    public function getRecoverPasswordEmail()
    {
        if (!$this->recoverPasswordEmail) {
            $this->setRecoverPasswordEmail(
                new RecoverPasswordEmail($this->account)
            );
        }
        return $this->recoverPasswordEmail;
    }

    /**
     * Set the recover e-mail message
     *
     * @param RecoverPasswordEmail $recoverPasswordEmail
     *
     * @return RecoverEmailSender
     */
    public function setRecoverPasswordEmail(
        RecoverPasswordEmail $recoverPasswordEmail
    ) {
        $this->recoverPasswordEmail = $recoverPasswordEmail;
        return $this;
    }

}
