<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Controller;

use Slick\Form\InputInterface;
use Slick\Users\Controller\Recover;
use Slick\Users\Exception\Accounts\UnknownEmailException;
use Slick\Users\Form\RecoverPasswordForm;
use Slick\Users\Service\Account\RecoverPasswordInterface;

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

    const EMAIL = 'jon.doe@example.com';

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

    /**
     * Should get the service from the dependency container
     * @test
     */
    public function getRecoverService()
    {
        $service = \Phake::mock(RecoverPasswordInterface::class);
        $dep = ['recoverPasswordService' => $service];
        $this->controller->setContainer(
            $this->getContainerMock($dep)
        );
        $this->assertSame($service, $this->controller->getRecoverService());
    }

    /**
     * Should set a variable with form object
     * @test
     */
    public function testSimpleRequest()
    {
        $form = $this->getMockedForm();
        $this->controller
            ->setRecoverForm($form)
            ->request();
        $this->assertVarSame($form, 'form');
    }

    /**
     * Should set an error flash message out
     * @test
     */
    public function testInvalidForm()
    {
        $form = $this->getMockedForm(false, true);
        $this->controller
            ->setRecoverForm($form)
            ->request();
        $this->assertErrorFlashMessageMatch(
            'Unable to send recover e-mail.'
        );
    }

    public function testUnknownEmail()
    {
        $form = $this->getMockedForm(true, true);
        $service = $this->getMockedService(new UnknownEmailException('Error'));
        $this->controller
            ->setRecoverForm($form)
            ->setRecoverService($service)
            ->request();
        $this->assertErrorFlashMessageMatch(
            'We don\'t have any account'
        );
        \Phake::verify($service)->setEmail(self::EMAIL);
    }

    public function testExceptionHandling()
    {
        $form = $this->getMockedForm(true, true);
        $service = $this->getMockedService(new \Exception('Error'));
        $this->controller
            ->setRecoverForm($form)
            ->setRecoverService($service)
            ->request();
        $this->assertErrorFlashMessageMatch(
            'Error when trying to send recover'
        );
    }

    public function testRecoverEmailSuccessfully()
    {
        $form = $this->getMockedForm(true, true);
        $input = \Phake::mock(InputInterface::class);
        \Phake::when($form)->get('email')->thenReturn($input);
        $service = $this->getMockedService();
        $this->controller
            ->setRecoverForm($form)
            ->setRecoverService($service)
            ->request();
        \Phake::verify($service)->requestEmail();
        \Phake::verify($input)->setValue('');
        $this->assertSuccessFlashMessageMatch(
            'An e-mail message with password recover'
        );
    }

    /**
     * Returns a mocked form
     *
     * @param bool $valid
     * @param bool $submitted
     *
     * @return \Phake_IMock|RecoverPasswordForm
     */
    protected function getMockedForm($valid = true, $submitted = false)
    {
        $email = self::EMAIL;
        /** @var RecoverPasswordForm|\Phake_IMock $form */
        $form = \Phake::mock(RecoverPasswordForm::class);
        \Phake::when($form)->isValid()->thenReturn($valid);
        \Phake::when($form)->wasSubmitted()->thenReturn($submitted);
        \Phake::when($form)->getEmail()->thenReturn($email);
        return $form;
    }

    /**
     * Get service mock
     *
     * @param \Exception|null $exc
     *
     * @return \Phake_IMock|RecoverPasswordInterface
     */
    protected function getMockedService(\Exception $exc = null)
    {
        /** @var RecoverPasswordInterface|\Phake_IMock $service */
        $service = \Phake::mock(
            RecoverPasswordInterface::class,
            $this->getSelfAnswer()
        );
        if ($exc) {
            \Phake::when($service)->requestEmail()->thenThrow($exc);
        }
        return $service;
    }
}
