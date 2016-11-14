<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Form;

use Slick\Form\FormInterface;

/**
 * Password Change Form Interface
 *
 * @package Slick\Users\Form
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface PasswordChangeFormInterface extends FormInterface
{

    /**
     * Gets the changed password
     *
     * @return string
     */
    public function getPassword();
}
