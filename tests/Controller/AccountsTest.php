<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Controller;

use Slick\Mvc\Form\EntityForm;
use Slick\Users\Controller\Accounts;

/**
 * Accounts controller test case
 *
 * @package Slick\Users\Tests\Controller
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class AccountsTest extends ControllerTestCase
{

    /**
     * @var Accounts
     */
    protected $controller;

    /**
     * Set the STU controller object
     */
    protected function setUp()
    {
        parent::setUp();
        $this->controller = new Accounts();
    }

    /**
     * Should lazy load the registration form
     * @test
     */
    public function getRegistrationForm()
    {
        $form = $this->controller->getRegisterForm();
        $this->assertInstanceOf(EntityForm::class, $form);
    }

    /**
     * Should register form in the view vars
     * @test
     */
    public function registerFormOnVars()
    {
        $form = $this->getMockedObject(EntityForm::class);
        $form->expects($this->once())
            ->method('wasSubmitted')
            ->willReturn(false);
        $this->controller->setRegisterForm($form);
        $this->controller->signUp();
        $this->assertSame($form, $this->controller->getViewVars()['form']);
    }

}
