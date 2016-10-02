<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Form;

use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_TestCase as TestCase;
use Slick\Mvc\Form\EntityForm;
use Slick\Users\Form\UsersForms;

/**
 * Users Forms Test Case
 *
 * @package Slick\Users\Tests\Form
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class UsersFormsTest extends TestCase
{
    /**
     * Should create a registration form
     */
    public function testGenerateRegisterForm()
    {
        $form = UsersForms::getRegisterForm();
        $this->assertInstanceOf(EntityForm::class, $form);
    }
}
