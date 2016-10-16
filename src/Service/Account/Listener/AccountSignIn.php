<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service\Account\Listener;

use League\Event\EventInterface;
use League\Event\ListenerInterface;
use Slick\Users\Domain\Account;
use Slick\Users\Service\Account\Authentication;
use Slick\Users\Service\Account\Authentication\SignedInAccount;
use Slick\Users\Service\Account\Event\SignIn;
use Slick\Users\Shared\Di\DependencyContainerAwareInterface;
use Slick\Users\Shared\Di\DependencyContainerAwareMethods;
use Slick\Users\Shared\Http\Session\SessionAwareInterface;
use Slick\Users\Shared\Http\Session\SessionAwareMethods;

/**
 * Account Sign In Listener
 *
 * @package Slick\Users\Service\Account\Listener
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class AccountSignIn implements
    ListenerInterface,
    DependencyContainerAwareInterface,
    SessionAwareInterface
{

    /**
     * Needed to use the dependency container
     */
    use DependencyContainerAwareMethods;

    /**
     * Needed to use session driver
     */
    use SessionAwareMethods;

    /**
     * Handle an event.
     *
     * @param EventInterface|SignIn $event
     *
     * @return void
     */
    public function handle(EventInterface $event)
    {
        $this->registerSessionData($event->getAccount());
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
        return $listener === $this;
    }

    /**
     * Register session data
     *
     * @param Account $account
     *
     * @return AccountSignIn
     */
    public function registerSessionData(Account $account)
    {
        $data = new SignedInAccount(['account' => $account]);
        $this->getSession()->set(Authentication::SESSION_KEY, $data);
        return $this;
    }
}
