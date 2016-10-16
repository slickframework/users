<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service\Account;

use League\Event\EmitterAwareInterface;
use League\Event\EmitterInterface;
use League\Event\ListenerProviderInterface;
use Psr\Log\LoggerInterface;
use Slick\Orm\Orm;
use Slick\Orm\RepositoryInterface;
use Slick\Users\Domain\Account;
use Slick\Users\Domain\Credential;
use Slick\Users\Service\Account\Event\SignIn;
use Slick\Users\Shared\Di\DependencyContainerAwareInterface;
use Slick\Users\Shared\Di\DependencyContainerAwareMethods;

/**
 * Authentication
 *
 * @package Slick\Users\Service\Account
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Authentication implements
    EmitterAwareInterface,
    DependencyContainerAwareInterface
{

    const SESSION_KEY= 'sign-in-data';

    /**
     * @var Account
     */
    protected $account;

    /**
     * @var AccountEventEmitter|EmitterInterface
     */
    protected $emitter;

    /**
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Needed to use dependency container
     */
    use DependencyContainerAwareMethods;

    /**
     * Register
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

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
        $encryptionService = new PasswordEncryptionService($password);
        return $encryptionService->match($credential->password);
    }

    /**
     * Set the Emitter.
     *
     * @param EmitterInterface $emitter
     *
     * @return $this
     */
    public function setEmitter(EmitterInterface $emitter = null)
    {
        $this->emitter = $emitter;
        return $this;
    }

    /**
     * Get the Emitter.
     *
     * @return EmitterInterface|AccountEventEmitter
     */
    public function getEmitter()
    {
        if (!$this->emitter) {
            /** @var AccountEventEmitter $emitter */
            $emitter = $this->getContainer()->get('accountEventEmitter');
            $this->setEmitter($emitter);
        }
        return $this->emitter;
    }
}
