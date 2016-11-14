<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service\Account;

use Slick\Users\Domain\Account;
use Slick\Users\Domain\Credential;
use Slick\Users\Domain\Token;
use Slick\Users\Exception\Accounts\InvalidTokenException;
use Slick\Users\Service\Account\Event\RecoveredPassword;

/**
 * Password Service
 *
 * @package Slick\Users\Service\Account
 * @author  Filipe Silva <filipe.silva@sata.pt>
 */
class ChangePasswordService extends AccountService implements
    ChangePasswordInterface
{
    /**
     * @var Token
     */
    protected $token;

    /**
     * @var Credential
     */
    protected $credential;

    /**
     * @var Account
     */
    protected $account;

    /**
     * Sets the token from the request
     *
     * @param Token $token
     * @return ChangePasswordService|ChangePasswordInterface
     */
    public function setToken(Token $token = null)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Change the password for provided account
     *
     * @param $password
     * @return ChangePasswordService|ChangePasswordInterface
     */
    public function change($password)
    {
        if (is_null($this->token) || !$this->token->isValid()) {
            throw new InvalidTokenException(
                "Invalid token given."
            );
        }
        $password = new PasswordEncryptionService($password);
        $this->getCredential()->password = $password;
        $this->getCredential()->save();
        $event = new RecoveredPassword($this->getAccount());
        $this->getEmitter()->emit($event);
        return $this;
    }

    /**
     * Get credential property
     *
     * @return Credential
     */
    public function getCredential()
    {
        if (!$this->credential) {
            $this->setCredential($this->getAccount()->credential);
        }
        return $this->credential;
    }

    /**
     * Set credential property
     *
     * @param Credential $credential
     *
     * @return ChangePasswordService
     */
    public function setCredential(Credential $credential)
    {
        $this->credential = $credential;
        return $this;
    }

    /**
     * Get account property
     *
     * @return Account
     */
    public function getAccount()
    {
        if (!$this->account) {
            $this->setAccount($this->token->account);
        }
        return $this->account;
    }

    /**
     * Set account property
     *
     * @param Account $account
     *
     * @return ChangePasswordService
     */
    public function setAccount(Account $account)
    {
        $this->account = $account;
        return $this;
    }
}
