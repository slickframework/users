<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Shared\Validator;

use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_TestCase as TestCase;
use Slick\Form\FormRegistry;
use Slick\Users\Shared\Validator\PasswordMatch;

class PasswordMatchTest extends TestCase
{

    /**
     * @var PasswordMatch
     */
    protected $validator;

    /**
     * Set the SUT validator object
     */
    protected function setUp()
    {
        parent::setUp();
        $this->validator = new PasswordMatch();
    }

    /**
     * Should pass when both passwords match
     */
    public function testPassValidation()
    {
        $form = $this->getForm();
        $form->get('password')->setValue('12345');
        $value = '12345';
        $ctx = ['form' => $form];
        $this->assertTrue($this->validator->validates($value, $ctx));
    }

    /**
     * Should fail when passwords don' match
     */
    public function testFailValidation()
    {
        $form = $this->getForm();
        $form->get('password')->setValue('12345');
        $value = '432121';
        $ctx = ['form' => $form];
        $this->assertFalse($this->validator->validates($value, $ctx));
    }

    /**
     * Get a form
     *
     * @return \Slick\Form\FormInterface
     */
    protected function getForm()
    {
        return FormRegistry::getForm(__DIR__.'/test-form.yml');
    }
}
