<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Form;

use Slick\Mvc\Form\EntityForm;

/**
 * Recover Password Form
 *
 * @package Slick\Users\Form
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class RecoverPasswordForm extends EntityForm
{

    /**
     * Get submitted e-mail address
     *
     * @return string
     */
    public function getEmail()
    {
        $data = $this->getData();
        return $data['email'];
    }
}
