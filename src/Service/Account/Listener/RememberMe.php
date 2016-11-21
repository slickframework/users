<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service\Account\Listener;

use League\Event\EventInterface;
use League\Event\ListenerInterface;

/**
 * Remember Me
 *
 * @package Slick\Users\Service\Account\Listener
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class RememberMe implements ListenerInterface
{

    /**
     * Handle an event.
     *
     * @param EventInterface $event
     *
     * @return void
     */
    public function handle(EventInterface $event)
    {
        // TODO: Implement handle() method.
    }

    /**
     * Check whether the listener is the given parameter.
     *
     * @param mixed $listener
     *
     * @return bool
     */
    public function isListener($listener)
    {
        // TODO: Implement isListener() method.
    }
}
