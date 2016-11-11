<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service\Account;

use Slick\Orm\Orm;
use Slick\Users\Domain\Account;
use Slick\Users\Domain\Repository\AccountsRepositoryInterface;
use Slick\Users\Exception\Accounts\UnknownEmailException;
use Slick\Users\Service\Account\Email\RecoverEmailSender;
use Slick\Users\Shared\Di\DependencyContainerAwareInterface;
use Slick\Users\Shared\Di\DependencyContainerAwareMethods;

/**
 * Recover Password Service
 *
 * @package Slick\Users\Service\Account
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class RecoverPasswordService implements
    RecoverPasswordInterface,
    DependencyContainerAwareInterface
{

    /**
     * @var AccountsRepositoryInterface
     */
    protected $accountRepository;

    /**
     * @var Account
     */
    protected $account;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var RecoverEmailSender
     */
    protected $recoverEmailSender;

    /**
     * To have access to dependency container
     */
    use DependencyContainerAwareMethods;

    /**
     * Sends out the e-mail message to the provided email address
     *
     * @return self|$this|RecoverPasswordInterface
     *
     * @throws UnknownEmailException
     */
    public function requestEmail()
    {
        if (!$this->getAccount()) {
            throw new UnknownEmailException(
                "No account for provided e-mail address: {$this->email}"
            );
        }
        $this->getRecoverEmailSender()->sendTo($this->getAccount());
        return $this;
    }

    /**
     * Get account repository
     *
     * @return AccountsRepositoryInterface
     */
    public function getAccountRepository()
    {
        if (!$this->accountRepository) {
            /** @var AccountsRepositoryInterface $repo */
            $repo = Orm::getRepository(Account::class);
            $this->setAccountRepository($repo);
        }
        return $this->accountRepository;
    }

    /**
     * Set account repository
     *
     * @param AccountsRepositoryInterface $accountRepository
     * @return RecoverPasswordService
     */
    public function setAccountRepository(
        AccountsRepositoryInterface $accountRepository
    ) {
        $this->accountRepository = $accountRepository;
        return $this;
    }

    /**
     * Get account for current e-mail address
     *
     * @return Account
     */
    public function getAccount()
    {
        if (!$this->account) {
            $this->setAccount(
                $this->getAccountRepository()
                    ->getByEmail($this->email)
            );
        }
        return $this->account;
    }

    /**
     * Set account to recover password
     *
     * @param Account $account
     *
     * @return RecoverPasswordService
     */
    public function setAccount(Account $account = null)
    {
        $this->account = $account;
        return $this;
    }

    /**
     * Set e-mail address
     *
     * @param string $email
     *
     * @return self|$this|RecoverPasswordInterface
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Get Recover Email Sender
     *
     * @return RecoverEmailSender
     */
    public function getRecoverEmailSender()
    {
        if (!$this->recoverEmailSender) {
            $this->setRecoverEmailSender(
                $this->getContainer()->get('recoverEmailSender')
            );
        }
        return $this->recoverEmailSender;
    }

    /**
     * Set Recover Email Sender
     *
     * @param RecoverEmailSender $recoverEmailSender
     * @return RecoverPasswordService
     */
    public function setRecoverEmailSender(RecoverEmailSender $recoverEmailSender)
    {
        $this->recoverEmailSender = $recoverEmailSender;
        return $this;
    }
}
