<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service\Account\Listener;

use League\Event\ListenerAcceptorInterface;
use League\Event\ListenerInterface;
use League\Event\ListenerProviderInterface;
use Slick\Users\Service\Account\Event\EmailChange;
use Slick\Users\Service\Account\Event\SignIn;
use Slick\Users\Service\Account\Event\SignUp;

/**
 * Account Events Provider
 *
 * @package Slick\Users\Service\Account\Listener
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class AccountEventsProvider implements ListenerProviderInterface
{

    /**
     * @var array List of listeners in this provider
     */
    private $listeners = [
        SignIn::NAME      => AccountSignIn::class,
        EmailChange::NAME => EmailChanged::class,
        SignUp::NAME      => EmailChanged::class
    ];

    /**
     * Provide event
     *
     * @param ListenerAcceptorInterface $listenerAcceptor
     *
     * @return $this
     */
    public function provideListeners(ListenerAcceptorInterface $listenerAcceptor)
    {
        foreach ($this->listeners as $event => $listenerClass) {
            /** @var ListenerInterface $listener */
            $listener = new $listenerClass();
            $listenerAcceptor->addListener($event, $listener);
        }
        return $this;
    }
}
