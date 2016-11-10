<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Form;

use Slick\Form\FormInterface;
use Slick\Form\FormRegistry;
use Slick\Mvc\Form\EntityForm;

/**
 * Users Forms
 *
 * @package Slick\Users\Form
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
final class UsersForms
{

    /**
     * Get account register form
     *
     * @return EntityForm|FormInterface|AccountRegisterForm
     */
    public static function getRegisterForm()
    {
        return FormRegistry::getForm(__DIR__.'/definitions/register-form.yml');
    }

    /**
     * Get account login form
     *
     * @return FormInterface|EntityForm|LoginForm
     */
    public static function getLoginForm()
    {
        return FormRegistry::getForm(__DIR__.'/definitions/login-form.yml');
    }

    /**
     * Get profile form
     *
     * @return ProfileFormInterface|ProfileForm
     */
    public static function getProfileForm()
    {
        /** @var ProfileFormInterface $form */
        $form = FormRegistry::getForm(__DIR__.'/definitions/profile-form.yml');
        return $form;
    }

    public static function getRecoverPasswordForm()
    {
        /** @var RecoverPasswordForm $form */
        $form = FormRegistry::getForm(__DIR__.'/definitions/recover-form.yml');
        return $form;
    }
}
