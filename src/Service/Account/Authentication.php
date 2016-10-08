<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service\Account;

use Slick\Orm\Orm;
use Slick\Orm\RepositoryInterface;
use Slick\Users\Domain\Account;
use Slick\Users\Domain\Credential;

/**
 * Authentication
 *
 * @package Slick\Users\Service\Account
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Authentication
{

    /**
     * @var Account
     */
    protected $account;

    /**
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * Check if provided username and password is from a valid account
     *
     * @param string $username
     * @param string $password
     *
     * @return boolean
     */
    public function login($username, $password)
    {
        $found = false;
        $credential = $this->getCredentialFor($username);
        if ($credential && $this->validate($credential, $password)) {
            $this->account = $credential->account;
            $found = true;
        }
        return $found;
    }

    /**
     * Get account for the last successful login() call
     *
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Gets repository property
     *
     * @return RepositoryInterface
     */
    public function getRepository()
    {
        if (!$this->repository) {
            $this->setRepository(Orm::getRepository(Account::class));
        }
        return $this->repository;
    }

    /**
     * Sets repository property
     *
     * @param RepositoryInterface $repository
     *
     * @return Authentication
     */
    public function setRepository(RepositoryInterface $repository)
    {
        $this->repository = $repository;
        return $this;
    }

    /**
     * Tries to find the credential for provided username|e-mail
     *
     * @param string $username Username or e-mail address
     *
     * @return Credential|null
     */
    protected function getCredentialFor($username)
    {
        /** @var Credential $credential */
        $credential = $this->getRepository()
            ->find()
            ->where(
                [
                    'email = :username OR username = :username' => [
                        ':username' => $username
                    ]
                ]
            )
            ->first()
        ;
        return $credential;
    }

    /**
     * Validates password against provided credential
     *
     * @param Credential $credential
     * @param string     $password
     *
     * @return boolean
     */
    protected function validate(Credential $credential, $password)
    {
        $encryptionService = new PasswordEncryptionService($password);
        return $encryptionService->match($credential->password);
    }

}
