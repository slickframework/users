<?php

/**
 * This file is part of Users.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service\Account\Event;

use League\Event\AbstractEvent;
use League\Event\EventInterface;
use Slick\Users\Domain\Account;

/**
 * Account Event
 *
 * @package Slick\Users\Service\Account\Event
 * @author  Filipe Silva <filipe.silva@sata.pt>
 */
abstract class AccountEvent extends AbstractEvent  implements EventInterface
{

    const NAME = 'account-event';

    /**
     * @var Account
     */
    protected $account;

    /**
     * SignIn
     *
     * @param Account $account
     */
    public function __construct(Account $account)
    {
        $this->account = $account;
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
