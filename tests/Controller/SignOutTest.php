<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Controller {

    use Slick\Users\Controller\SignOut;
    use Slick\Users\Tests\Controller\ControllerTestCase;

    /**
     * SignOut controller test case
     *
     * @package Slick\Users\Tests\Controller
     * @author  Filipe Silva <silvam.filipe@gmail.com>
     */
    class SignOutTest extends ControllerTestCase
    {

        /**
         * @var SignOutController
         */
        protected $controller;

        protected function setUp()
        {
            $this->controller = new SignOutController();
            parent::setUp();
        }

        public function testHandle()
        {
            $this->controller->handle();
            $this->assertRedirectTo('/');
        }
    }

    /**
     * Mocker session regenerate ID
     *
     * @param $renew
     */
    function session_regenerate_id($renew)
    {
        \PHPUnit_Framework_Assert::assertTrue($renew);
    }

    /**
     * SignOutController
     *
     * @package Slick\Users\Tests\Controller
     * @author  Filipe Silva <silvam.filipe@gmail.com>
     */
    class SignOutController extends SignOut {}
}
