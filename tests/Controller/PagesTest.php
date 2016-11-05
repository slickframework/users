<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Controller;
use Slick\Users\Controller\Pages;

/**
 * PagesTest
 *
 * @package Slick\Users\Tests\Controller
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class PagesTest extends ControllerTestCase
{
    /**
     * @var Pages
     */
    protected $controller;

    /**
     * Set up the SUT controller object
     */
    protected function setUp()
    {
        $this->controller = new Pages();
        parent::setUp();
    }

    /**
     * Should do nothing
     * @test
     */
    public function homeHandle()
    {
        $this->controller->home();
        $this->assertEmptyVars();
    }

    public function testForbidden()
    {
        $this->controller->forbidden();
        $this->assertEquals(403, $this->controller->getResponse()->getStatusCode());
    }
}
