<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service\Account\Email;

use Slick\Users\Service\Account\Email\Message\EmailMessageInterface;
use Slick\Users\Service\Email\EmailTransportInterface;

/**
 * AccountEmailSenderInterface
 *
 * @package Slick\Users\Service\Account\Email
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface AccountEmailSenderInterface
{

    /**
     * Set message to be sent
     *
     * @param EmailMessageInterface $message
     *
     * @return self|$this|AccountEmailSenderInterface
     */
    public function setMessage(EmailMessageInterface $message);

    /**
     * Sends out the e-mail message
     *
     * This method should return a boolean true if the message was successfully
     * delivered to the email transport agent.
     *
     * @return boolean
     */
    public function send();

    /**
     * Set email transport agent
     *
     * @param EmailTransportInterface $transport
     *
     * @return self|$this|AccountEmailSenderInterface
     */
    public function setEmailTransport(EmailTransportInterface $transport);
}
