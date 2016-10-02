<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Shared\Validator;

use Slick\Mvc\Form\EntityForm;
use Slick\Validator\AbstractValidator;
use Slick\Validator\ValidatorInterface;

/**
 * Password Match Validator
 *
 * @package Slick\Users\Shared\Validator
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class PasswordMatch extends AbstractValidator implements ValidatorInterface
{

    /**
     * @var string string
     */
    protected $messageTemplate = "Passwords don't match.";

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
        /** @var EntityForm $form */
        $form = $context['form'];
        $password = $form->get('password')->getValue();
        $result = $password === $value;
        if (!$result) {
            $this->addMessage($this->messageTemplate);
        }
        return $result;
    }
}