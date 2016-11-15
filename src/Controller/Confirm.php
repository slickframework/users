<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Controller;

use Slick\Users\Domain\Account;
use Slick\Users\Service\Account\Email\AccountEmailSenderInterface;

/**
 * Confirm e-mail request controller
 *
 * @package Slick\Users\Controller
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Confirm extends AccountAwareController
{

    /**
     * @var AccountEmailSenderInterface
     */
    protected $emailSender;

    /**
     * Handles the request to send a confirmation e-mail to provided account
     */
    public function handle()
    {
        $account = $this->getAccount();
        $data = ['sent' => false];
        if ($account instanceof Account) {
            $data['sent'] = true;
            $this->getEmailSender()->sendTo($account);
        }

        $this->addSuccessMessage(
            $this->translate(
                'An e-mail message will be sent to the e-mail address in ' .
                'your account. Use it to confirm your e-mail address.'
            )
        );
        $this->redirect($this->request->getHeaderLine('referer'));
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
     * @return Confirm
     */
    public function setEmailSender(AccountEmailSenderInterface $emailSender)
    {
        $this->emailSender = $emailSender;
        return $this;
    }

}
