<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service\Account;

use League\Event\EmitterAwareInterface;
use League\Event\EmitterInterface;
use Slick\Users\Domain\Account;
use Slick\Users\Service\Account\Event\EmailChange;
use Slick\Users\Shared\Di\DependencyContainerAwareInterface;

/**
 * Profile Update Service
 *
 * @package Slick\Users\Service\Account
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class ProfileUpdater extends AccountService implements
    ProfileUpdaterInterface,
    EmitterAwareInterface,
    DependencyContainerAwareInterface
{

    /**
     * Updates the provided CHANGED account
     *
     * @param Account $account
     *
     * @return $this|ProfileUpdater
     */
    public function update(Account $account)
    {
        $account->confirmed = 0;
        $credential = $account->credential;
        if ($credential->email !== $account->email) {
            $account->confirmed = 0;
            $credential->email = $account->email;
            $credential->save();
            $this->emitEmailChange($account);
        }
        $account->save();
        return $this;
    }

    /**
     * Emits the e-mail change account event
     *
     * @param Account $account
     *
     * @return ProfileUpdater
     */
    protected function emitEmailChange(Account $account)
    {
        $event = new EmailChange($account);
        $this->getEmitter()->emit($event);
        return $this;
    }
}
