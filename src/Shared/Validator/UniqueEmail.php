<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Shared\Validator;

use Slick\Filter\StaticFilter;
use Slick\Orm\Orm;
use Slick\Orm\Repository\EntityRepository;
use Slick\Users\Domain\Account;
use Slick\Users\Form\ProfileForm;
use Slick\Validator\AbstractValidator;
use Slick\Validator\ValidatorInterface;

/**
 * Unique Email Validator
 *
 * @package Slick\Users\Shared\Validator
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class UniqueEmail extends AbstractValidator implements ValidatorInterface
{

    /**
     * @var EntityRepository
     */
    protected $accountsRepository;

    /**
     * @var array Error messages templates
     */
    protected $messageTemplate =
        'The e-mail address "%s" already exist in our database.';

    /**
     * Returns true if and only if $value meets the validation requirements
     *
     * The context specified can be used in the validation process so that
     * the same value can be valid or invalid depending on that data.
     *
     * @param mixed $value
     * @param array|mixed $context
     *
     * @return bool
     */
    public function validates($value, $context = [])
    {
        $result = $this->checkEmail($value, $this->getAccountId($context));
        if (!$result) {
            $this->addMessage($this->messageTemplate, $value);
        }
        return $result;
    }

    /**
     * Gets usersRepository property
     *
     * @return EntityRepository
     */
    public function getAccountsRepository()
    {
        if (!$this->accountsRepository) {
            $this->setAccountsRepository(Orm::getRepository(Account::class));
        }
        return $this->accountsRepository;
    }

    /**
     * Sets usersRepository property
     *
     * @param EntityRepository $accountsRepository
     *
     * @return UniqueEmail
     */
    public function setAccountsRepository(EntityRepository $accountsRepository)
    {
        $this->accountsRepository = $accountsRepository;
        return $this;
    }

    /**
     * Check if provided value is a unique email in accounts table
     *
     * @param string $value
     *
     * @return bool
     */
    protected function checkEmail($value, $entityId)
    {
        $count = $this->getAccountsRepository()
            ->find()
            ->where(['email = :email' => [':email' => $value]])
            ->andWhere(['id <> :id' => [':id' => $entityId]])
            ->count();
        return $count == 0;
    }

    /**
     * Get account id from context
     *
     * @param array $context
     *
     * @return int
     */
    protected function getAccountId($context = [])
    {
        $id = array_key_exists('id', $context)
            ? StaticFilter::filter('text', $context['id'])
            : 0;
        /** @var ProfileForm $form */
        $form = array_key_exists('form', $context)
            ? $context['form']
            : null;
        if ($form && $form->get('id')) {
            $id = $form->get('id')->getValue();
        }
        return $id;
    }
}
