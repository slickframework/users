<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Form;

use Slick\Users\Form\PasswordChangeForm;
use Slick\Users\Tests\TestCase;

/**
 * Password Change Form Test Case
 *
 * @package Slick\Users\Tests\Form
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class PasswordChangeFormTest extends TestCase
{

    public function testGetPassword()
    {
        /** @var PasswordChangeForm|\Phake_IMock $form */
        $form = \Phake::partialMock(PasswordChangeForm::class);
        $data = ['password' => '12345'];
        \Phake::when($form)->getData()->thenReturn($data);
        $this->assertEquals($data['password'], $form->getPassword());
    }
}
