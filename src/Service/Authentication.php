<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service;

use Slick\Users\Domain\Account;
use Slick\Users\Service\Account\Authentication\SignedInAccount;
use Slick\Users\Shared\Di\DependencyContainerAwareInterface;
use Slick\Users\Shared\Di\DependencyContainerAwareMethods;
use Slick\Users\Shared\Http\Session\SessionAwareInterface;
use Slick\Users\Shared\Http\Session\SessionAwareMethods;
use Slick\Users\Service\Account\Authentication as SignInService;

/**
 * Authentication
 *
 * @package Slick\Users\Service
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Authentication implements
    DependencyContainerAwareInterface,
    SessionAwareInterface
{

    /**
     * Needed for session retrieval
     */
    use SessionAwareMethods;

    /**
     * Needed to ge session driver
     */
    use DependencyContainerAwareMethods;

    /**
     * @var SignedInAccount
     */
    protected $signedInAccount;

    /**
     * Check if current account is a guest account
     *
     * @return bool
     */
    public function isGuest()
    {
        return $this->getSignedInAccount()->isGuest();
    }

    /**
     * Get current signed in account
     *
     * @return Account
     */
    public function getCurrentAccount()
    {
        return $this->getSignedInAccount()->account;
    }

    /**
     * Get get user data
     *
     * @return SignedInAccount
     */
    protected function getGuestAccount()
    {
        return new SignedInAccount(
            [
                'account' => new Account(['id' => 0, 'name' => 'Anonymous']),
                'signedIn' => false,
                'guest' => true
            ]
        );
    }

    /**
     * Get current signed in account
     *
     * @return SignedInAccount
     */
    protected function getSignedInAccount()
    {
        if (!$this->signedInAccount) {
            $this->signedInAccount = $this->getSession()->get(
                SignInService::SESSION_KEY,
                $this->getGuestAccount()
            );
        }
        return $this->signedInAccount;
    }

}
