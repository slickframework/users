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

/**
 * Recover Password Service
 *
 * @package Slick\Users\Service\Account
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class RecoverPasswordService implements RecoverPasswordInterface
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
     * Sends out the e-mail message to the provided email address
     *
     * @return self|$this|RecoverPasswordInterface
     *
     * @throws UnknownEmailException
     */
    public function requestEmail()
    {
        if (!$this->account) {
            throw new UnknownEmailException(
                "No account for provided e-mail address: {$this->email}"
            );
        }
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
    public function setAccount(Account $account)
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
}
