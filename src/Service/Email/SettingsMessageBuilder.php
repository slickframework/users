<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service\Email;

use Slick\Mail\MessageBody;
use Slick\Mail\MessageBodyInterface;
use Slick\Mail\MessageInterface;
use Slick\Mail\Mime;
use Slick\Mail\Mime\MimeMessage;
use Slick\Mail\Mime\Part;

/**
 * Settings Message Builder
 *
 * Builds an e-mail message body from an array of settings
 *
 * @package Slick\Users\Service\Email
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class SettingsMessageBuilder implements MessageBodyBuilderInterface
{
    /**
     * @var array
     */
    protected $variables = [];

    /**
     * @var array
     */
    protected $settings = [];

    /**
     * @var MessageBodyInterface|MimeMessage
     */
    protected $message;

    /**
     * Create an e-mail message body to provided message
     *
     * @param MessageInterface $message
     * @param array $settings
     *
     * @return self|SettingsMessageBuilder
     */
    public function build(MessageInterface $message, array $settings)
    {
        $this->settings = $settings;
        $this->message = $message;
        $this
            ->addHtmlPart()
            ->addTextPart()
            ->addEmbedParts()
        ;
        return $this;
    }

    /**
     * Sets a variable to bu used in the templates
     *
     * @param string $variable
     * @param mixed $value
     *
     * @return self|SettingsMessageBuilder
     */
    public function set($variable, $value)
    {
        $this->variables[$variable] = $value;
        return $this;
    }

    /**
     * Get get current message
     *
     * Return MessageInterface
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Adds a mime html part to message
     *
     * @return $this|self|SettingsMessageBuilder
     */
    protected function addHtmlPart()
    {
        $isHtml = array_key_exists('html', $this->settings) &&
            $this->message instanceof MimeMessage;

        if ($isHtml) {
            $this->createPart('html', 'text/html');
        }
        return $this;
    }

    /**
     * Adds a mime text/plain part to message
     *
     * @return $this|self|SettingsMessageBuilder
     */
    protected function addTextPart()
    {
        if ($this->message instanceof MimeMessage) {
            return $this->createPart('plain', 'text/plain');
        }

        $body = new MessageBody($this->settings['plain'], $this->variables);
        $this->message->setBody($body);
        return $this;
    }

    /**
     * Add all embed items defined in settings
     *
     * @return $this|self|SettingsMessageBuilder
     */
    protected function addEmbedParts()
    {
        if (!array_key_exists('embed', $this->settings)) {
            return $this;
        }

        foreach ($this->settings['embed'] as $id => $item) {
            $part = $this->getPart($item);
            $part->setId($id);
            $this->message->parts()->add($part);
        }
        return $this;
    }

    /**
     * Get the embed mime part from item
     *
     * @param array $item
     *
     * @return Part
     */
    protected function getPart(array $item)
    {
        $part = null;
        foreach ($item as $mime => $filename) {
            $part = new Part($filename);
            $part->setEncoding(Mime::ENCODING_BASE64)
                ->setType($mime);
        }
        return $part;
    }

    /**
     * Set a mime part into the message
     *
     * @param string $key
     * @param string $mime
     *
     * @return $this|self|SettingsMessageBuilder
     */
    protected function createPart($key, $mime)
    {
        $template = $this->settings[$key];
        $part = new Part($template, $this->variables);
        $part->setType($mime);
        $this->message->parts()->add($part);
        return $this;
    }
}
