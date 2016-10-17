<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Form;

use Slick\Mvc\Form\EntityForm;
use Slick\Users\Service\Account\Register\RegisterRequest;

/**
 * Account Register Form
 *
 * @package Slick\Users\Form
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class AccountRegisterForm extends EntityForm
{

    /**
     * Returns submitted data or current data
     *
     * @return RegisterRequest
     */
    public function getData()
    {
        $data = parent::getData();
        return new RegisterRequest(
            $data['email'],
            $data['password'],
            $data['name']
        );
    }
}
