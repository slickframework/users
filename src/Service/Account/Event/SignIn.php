<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service\Account\Event;

use League\Event\AbstractEvent;
use League\Event\EventInterface;
use Slick\Users\Domain\Account;

/**
 * Sign In event
 *
 * @package Slick\Users\Service\Account\Event
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class SignIn extends AbstractEvent implements EventInterface
{

    const NAME = 'sign-in';

    /**
     * @var Account
     */
    private $account;

    /**
     * SignIn
     *
     * @param Account $loggedIAccount
     */
    public function __construct(Account $loggedIAccount)
    {
        $this->account = $loggedIAccount;
    }

    /**
     * Get the event name.
     *
     * @return string
     */
    public function getName()
    {
        return static::NAME;
    }

    /**
     * Gets signed in account
     *
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }

}
