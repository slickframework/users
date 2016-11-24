<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service\Account;

use League\Event\EmitterAwareInterface;
use Slick\Orm\Orm;
use Slick\Orm\RepositoryInterface;
use Slick\Users\Domain\Account;
use Slick\Users\Domain\Credential;
use Slick\Users\Domain\Token;
use Slick\Users\Service\Account\Event\SignIn;
use Slick\Users\Shared\Configuration\SettingsAwareInterface;
use Slick\Users\Shared\Configuration\SettingsAwareMethods;
use Slick\Users\Shared\DataType\DateTime;
use Slick\Users\Shared\Di\DependencyContainerAwareInterface;

/**
 * Authentication
 *
 * @package Slick\Users\Service\Account
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Authentication extends AccountService implements
    EmitterAwareInterface,
    DependencyContainerAwareInterface,
    SettingsAwareInterface
{

    use SettingsAwareMethods;

    const SESSION_KEY = 'sign-in-data';

    /**
     * @var Account
     */
    protected $account;

    /**
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * @var PasswordEncryptionInterface
     */
    protected $encryptionService;

    /**
     * @var CookieTokenStorageInterface
     */
    protected $cookieService;

    /**
     * @var Token
     */
    protected $token;

    /**
     * Check if provided username and password is from a valid account
     *
     * @param string $username
     * @param string $password
     * @param boolean $remember
     *
     * @return boolean
     */
    public function login($username, $password, $remember = false)
    {
        $credential = $this->getCredentialFor($username);
        if ($credential && $this->validate($credential, $password)) {
            session_regenerate_id(true);
            $this->account = $credential->account;
            $event = new SignIn($this->account);
            $this->getEmitter()->emit($event);
            $this->logger->info(
                "User {$this->account} has successful signed in.",
                $this->account->asArray()
            );
            $this->remember($remember);
            return true;
        }
        $this->logger->info(
            "Unsuccessful signed in attempt.",
            [
                'username' => $username
            ]
        );
        return false;
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
     * Set account
     *
     * @param Account $account
     *
     * @return Authentication
     */
    public function setAccount(Account $account)
    {
        $this->account = $account;
        return $this;
    }


    /**
     * Gets repository property
     *
     * @return RepositoryInterface
     */
    public function getRepository()
    {
        if (!$this->repository) {
            $this->setRepository(Orm::getRepository(Credential::class));
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
                    'credentials.email = :username OR
                     username = :username' => [
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
        $this->getEncryptionService()->setPassword($password);
        return $this->getEncryptionService()->match($credential->password);
    }

    /**
     * Get password encryption service
     *
     * @return PasswordEncryptionInterface
     */
    public function getEncryptionService()
    {
        if (!$this->encryptionService) {
            $this->setEncryptionService(
                $this->getContainer()->get('passwordEncryptionService')
            );
        }
        return $this->encryptionService;
    }

    /**
     * Set password encryption service
     *
     * @param PasswordEncryptionInterface $encryptionService
     * @return Authentication
     */
    public function setEncryptionService(
        PasswordEncryptionInterface $encryptionService
    ) {
        $this->encryptionService = $encryptionService;
        return $this;
    }

    /**
     * Remember last logged in user
     *
     * @param boolean $remember
     *
     * @return $this|Authentication
     */
    public function remember($remember = true)
    {
        if (!$remember) return $this;
        $this->getCookieService()->set('users-rmm', $this->getToken());
        $this->getToken()->save();
        return $this;
    }

    /**
     * Get cookie service
     *
     * @return CookieTokenStorageInterface
     */
    public function getCookieService()
    {
        if (!$this->cookieService) {
            $this->setCookieService(new CookieTokenStorageService());
        }
        return $this->cookieService;
    }

    /**
     * Set cookie service
     *
     * @param CookieTokenStorageInterface $cookieService
     * @return Authentication
     */
    public function setCookieService(
        CookieTokenStorageInterface $cookieService
    ) {
        $this->cookieService = $cookieService;
        return $this;
    }

    /**
     * Get token
     *
     * @return Token
     */
    public function getToken()
    {
        if (!$this->token) {
            $ttl = new DateTime(
                time()+$this->getSettings()->get('rememberMe.expire')
            );
            $this->setToken(new Token(
                [
                    'account' => $this->getAccount(),
                    'action' => Token::ACTION_REMEMBER,
                    'ttl' => $ttl
                ]
            ));
        }
        return $this->token;
    }

    /**
     * Set token
     *
     * @param Token $token
     *
     * @return Authentication
     */
    public function setToken(Token $token)
    {
        $this->token = $token;
        return $this;
    }
}
