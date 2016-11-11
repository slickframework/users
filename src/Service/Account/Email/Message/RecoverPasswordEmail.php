<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service\Account\Email\Message;

use Slick\Users\Domain\Token;

/**
 * Recover Password E-mail
 *
 * @package Slick\Users\Service\Account\Email\Message
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class RecoverPasswordEmail extends ConfirmAccountEmail implements
    EmailMessageInterface
{

    /**
     * Prepares message body
     *
     * @return $this|self
     */
    protected function body()
    {
        $this->getToken()->account = $this->account;
        $this->getToken()->action = Token::ACTION_RECOVER;
        $this->getMessageBuilder()
            ->set('account', $this->account)
            ->set('token', $this->getToken())
        ;
        $this->getMessageBuilder()
            ->build(
                $this,
                $this->getSettings()->get('email.messages.recover', [])
            )
        ;
        $this->getToken()->save();
        return $this;
    }

    /**
     * Prepares message subject
     *
     * @return $this|self
     */
    protected function subject()
    {
        list($markers, $values) = $this->getPlaceholders();
        $subject = str_replace(
            $markers,
            $values,
            $this->translate(
                $this->getSettings()->get('email.subjects.recover')
            )
        );
        $this->setSubject($subject);
        return $this;
    }
}
