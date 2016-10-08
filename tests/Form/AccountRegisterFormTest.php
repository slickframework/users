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
use Slick\Users\Form\UsersForms;
use Slick\Users\Service\Account\Register\RegisterRequest;

class AccountRegisterFormTest extends TestCase
{

    /**
     * Should create a register request and return it
     * @test
     */
    public function dataReturnsRegisterRequest()
    {
        $data = [
            'email' => 'jon.doe@example.com',
            'name' => 'Jon Doe',
            'password' => '123456'
        ];
        $form = UsersForms::getRegisterForm();
        $request = $form->setData($data)->getData();

        $this->assertInstanceOf(RegisterRequest::class, $request);
    }
}
