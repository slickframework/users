<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service\Email;

use Slick\Mail\MessageInterface;
use Slick\Mail\Transport\MailTransportInterface;
use Slick\Mail\Transport\PhpMailTransport;
use Slick\Users\Shared\Configuration\SettingsAwareInterface;
use Slick\Users\Shared\Configuration\SettingsAwareMethods;

/**
 * Slick Mail Transport
 *
 * @package Slick\Users\Service\Email
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class SlickMailTransport implements
    EmailTransportInterface,
    SettingsAwareInterface
{

    /**
     * To access settings
     */
    use SettingsAwareMethods;

    /**
     * @var MailTransportInterface
     */
    protected $transporter;

    /**
     * Send a e-mail message
     *
     * @param MessageInterface $message
     *
     * @return MailTransportInterface
     */
    public function send(MessageInterface $message)
    {
        return $this->getTransporter()->send($message);
    }

    /**
     * @return MailTransportInterface
     */
    public function getTransporter()
    {
        if (!$this->transporter) {
            $this->setTransporter($this->createTransporter());
        }
        return $this->transporter;
    }

    /**
     * @param MailTransportInterface $transporter
     * @return SlickMailTransport
     */
    public function setTransporter($transporter)
    {
        $this->transporter = $transporter;
        return $this;
    }

    /**
     * Factory method for mail transporter
     *
     * @return MailTransportInterface
     */
    protected function createTransporter()
    {
        $options = $this->getSettings()->get('email.transport', []);
        $class = $this->getSettings()
            ->get('email.transport.class', PhpMailTransport::class);
        /** @var MailTransportInterface $transport */
        $transport = (array_key_exists('args',$options))
            ? new $class($options['args'])
            : new $class();
        return $transport;
    }

}
