<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Form;

use Slick\Users\Form\RecoverPasswordForm;
use Slick\Users\Tests\TestCase;

/**
 * Recover Password Form Test
 *
 * @package Slick\Users\Tests\Form
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class RecoverPasswordFormTest extends TestCase
{

    public function testGetMail()
    {
        $data = ['email' => 'jon.doe@example.com'];
        /** @var RecoverPasswordForm|\Phake_IMock $form */
        $form = \Phake::partialMock(RecoverPasswordForm::class);
        \Phake::when($form)->getData()->thenReturn($data);
        $this->assertEquals($data['email'], $form->getEmail());
    }
}
