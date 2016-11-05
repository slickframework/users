<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service\Email;

use Slick\Mail\MessageBodyInterface;
use Slick\Mail\MessageInterface;

/**
 * Message Body Builder Interface
 *
 * @package Slick\Users\Service\Email
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface MessageBodyBuilderInterface
{

    /**
     * Create an e-mail message body to provided message
     *
     * @param MessageInterface $message
     * @param array $settings
     *
     * @return self|$this|MessageBodyBuilderInterface
     */
    public function build(MessageInterface $message, array $settings);

    /**
     * Sets a variable to bu used in the templates
     *
     * @param string $variable
     * @param mixed $value
     *
     * @return self|$this|MessageBodyBuilderInterface
     */
    public function set($variable, $value);
}
