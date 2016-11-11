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
            $this->getRecoverEmail($account)->prepareMessage()
        );
        return true;
    }

    /**
     * Get a confirmation e-mail message object
     *
     * @param Account $account
     *
     * @return RecoverPasswordEmail
     */
    public function getRecoverEmail(Account $account)
    {
        return new RecoverPasswordEmail($account);
    }
}
