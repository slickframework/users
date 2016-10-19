<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Form;

use Slick\Users\Domain\Account;
use Slick\Users\Form\UsersForms;
use Slick\Users\Service\Authentication;
use Slick\Users\Tests\TestCase;

/**
 * ProfileFormTest
 *
 * @package Slick\Users\Tests\Form
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class ProfileFormTest extends TestCase
{

    public function testAccountEditing()
    {
        $form = UsersForms::getProfileForm();
        $account = new Account();
        $result = $form->setAccount($account);
        $this->assertSame($form, $result);
        $this->assertSame($account, $result->getAccount());
    }

    public function testAccountFromAuthenticationService()
    {
        $account = new Account();
        $auth = \Phake::mock(Authentication::class);
        \Phake::when($auth)->getCurrentAccount()->thenReturn($account);
        $form = UsersForms::getProfileForm();
        $form->setAuthenticationService($auth);
        $this->assertSame($account, $form->getAccount());
    }
}
