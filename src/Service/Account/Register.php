<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service\Account;

use Slick\Common\Base;
use Slick\Users\Domain\Account;
use Slick\Users\Domain\Credential;
use Slick\Users\Service\Account\Register\RegisterRequest;

/**
 * Register
 *
 * @package Slick\Users\Service\Account
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property Account    $account
 * @property Credential $credential
 * @property RegisterRequest $registerRequest
 */
class Register extends Base
{

    /**
     * @readwrite
     * @var RegisterRequest
     */
    protected $registerRequest;

    /**
     * @readwrite
     * @var Account
     */
    protected $account;

    /**
     * @readwrite
     * @var Credential
     */
    protected $credential;

    /**
     * Registers the new user account
     *
     * @param RegisterRequest $registerRequest
     */
    public function execute(RegisterRequest $registerRequest)
    {
        $this->registerRequest = $registerRequest;
        $account = $this->getAccount();
        $account->save();
        $credential = $this->getCredential();
        $credential->account = $account;
        $credential->save();
    }

    /**
     * Gets credential property
     *
     * @return Credential
     */
    public function getCredential()
    {
        if (!$this->credential) {
            $this->credential = new Credential();
        }
        return $this->credential->hydrate($this->getCredentialData());
    }

    /**
     * Gets account property
     *
     * @return Account
     */
    public function getAccount()
    {
        if (!$this->account) {
            $this->account = new Account();
        }
        return $this->account->hydrate($this->getAccountData());
    }

    /**
     * Get data to be assign to the credential entity
     *
     * @return array
     */
    protected function getCredentialData()
    {
        $emailSplit = explode('@', $this->registerRequest->email);
        return [
            'password' => (string) $this->registerRequest->password,
            'email' => $this->registerRequest->email,
            'username' => $emailSplit[0]
        ];
    }

    /**
     * Get data to be assign to the account entity
     *
     * @return array
     */
    protected function getAccountData()
    {
        return [
            'name' => $this->registerRequest->name,
            'email' => $this->registerRequest->email,
        ];
    }
}
