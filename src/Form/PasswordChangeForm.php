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
 * PasswordChangeForm
 *
 * @package Slick\Users\Form
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class PasswordChangeForm extends EntityForm implements PasswordChangeFormInterface
{

    /**
     * Gets the changed password
     *
     * @return string
     */
    public function getPassword()
    {
        $data = $this->getData();
        return $data['password'];
    }
}
