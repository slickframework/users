<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service\Account\Email\Message;

use Slick\I18n\TranslateMethods;
use Slick\Mail\Mime\MimeMessage;
use Slick\Users\Domain\Account;
use Slick\Users\Service\Email\MessageBodyBuilderInterface;
use Slick\Users\Shared\Configuration\SettingsAwareInterface;
use Slick\Users\Shared\Configuration\SettingsAwareMethods;
use Slick\Users\Shared\Di\DependencyContainerAwareInterface;
use Slick\Users\Shared\Di\DependencyContainerAwareMethods;

/**
 * Confirm Account Email
 *
 * @package Slick\Users\Service\Account\Email\Message
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class ConfirmAccountEmail extends MimeMessage implements
    EmailMessageInterface,
    SettingsAwareInterface,
    DependencyContainerAwareInterface
{

    /**
     * @var Account
     */
    protected $account;

    /**
     * @var MessageBodyBuilderInterface
     */
    protected $messageBuilder;

    /**
     * To gain access to application settings
     */
    use SettingsAwareMethods;

    /**
     * Used to translate subject
     */
    use TranslateMethods;

    /**
     * To use dependency container
     */
    use DependencyContainerAwareMethods;

    /**
     * Confirm Account E-mail
     *
     * @param Account $account
     */
    public function __construct(Account $account)
    {
        $this->account = $account;
        parent::__construct();
    }

    /**
     * Prepares the message before it can be used
     *
     * @return self|$this|ConfirmAccountEmail|EmailMessageInterface
     */
    public function prepareMessage()
    {
        $this->setTo($this->account->email, $this->account->name);
        $this->setFrom($this->getSettings()->get('email.from'));
        $this->subject()->body();

        return $this;
    }

    /**
     * Get message body builder service
     *
     * @return MessageBodyBuilderInterface
     */
    public function getMessageBuilder()
    {
        if (!$this->messageBuilder) {
            $this->setMessageBuilder(
                $this->getContainer()->get('settingsMessageBuilder')
            );
        }
        return $this->messageBuilder;
    }

    /**
     * Set message body builder service
     *
     * @param MessageBodyBuilderInterface $messageBuilder
     * @return ConfirmAccountEmail
     */
    public function setMessageBuilder($messageBuilder)
    {
        $this->messageBuilder = $messageBuilder;
        return $this;
    }

    /**
     * Prepares message body
     *
     * @return $this|self
     */
    protected function body()
    {
        $this->getMessageBuilder()->set('account', $this->account);
        $this->getMessageBuilder()
            ->build(
                $this,
                $this->getSettings()->get('email.messages.confirmation', [])
            )
        ;
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
                $this->getSettings()->get('email.subjects.confirmation')
            )
        );
        $this->setSubject($subject);
        return $this;
    }

    /**
     * Get the account placeholders
     *
     * @return array
     */
    protected function getPlaceholders()
    {
        $placeholders = [
            ':name' => $this->account->name,
            ':email' => $this->account->email
        ];
        return [
            array_keys($placeholders),
            array_values($placeholders)
        ];
    }
}
