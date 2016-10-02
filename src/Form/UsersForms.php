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
     * @return EntityForm|FormInterface
     */
    public static function getRegisterForm()
    {
        return FormRegistry::getForm(__DIR__.'/definitions/register-form.yml');
    }
}
