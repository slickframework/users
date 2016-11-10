<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Controller;

use Slick\Users\Controller\Recover;
use Slick\Users\Form\RecoverPasswordForm;

/**
 * Recover Controller Test Case
 * @package Slick\Users\Tests\Controller
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class RecoverTest extends ControllerTestCase
{

    /**
     * @var Recover
     */
    protected $controller;

    /**
     * Set the SUT controller object
     */
    protected function setUp()
    {
        $this->controller = new Recover();
        parent::setUp();
    }

    /**
     * Should use the Users form factory to get the recover form
     * @test
     */
    public function getRecoverForm()
    {
        $form = $this->controller->getRecoverForm();
        $this->assertInstanceOf(RecoverPasswordForm::class, $form);
    }
}
