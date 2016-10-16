<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Controller;

use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Slick\Http\PhpEnvironment\Request;
use Slick\Http\PhpEnvironment\Response;
use Slick\Mvc\Controller;
use Slick\Mvc\Http\FlashMessages;
use Slick\Users\Tests\TestCase;

/**
 * ControllerTestCase
 *
 * @package Slick\Users\Tests\Controller
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class ControllerTestCase extends TestCase
{

    /**
     * @var Controller
     */
    protected $controller;

    /**
     * @var FlashMessages
     */
    protected $flashMessages;

    /**
     * Set the SUT controller object
     */
    protected function setUp()
    {
        parent::setUp();
        $this->controller->register(new Request(), new Response());
    }

    /**
     * Asserts controller's view variables array is empty
     *
     * @param string $message
     */
    public function assertEmptyVars($message = '')
    {
        $this->assertEmpty($this->controller->getViewVars(), $message);
    }

    /**
     * Get a mock object for provided class name
     *
     * @param $className
     *
     * @return MockObject
     */
    protected function getMockedObject($className)
    {
        $methods = get_class_methods($className);
        $object = $this->getMockBuilder($className)
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();
        return $object;
    }

    /**
     * Gets controller FlashMessages object
     *
     * @return FlashMessages
     */
    private function getFlashMessages()
    {
        if (!$this->flashMessages) {
            $this->checkFlashMessages();
        }
        return $this->flashMessages;
    }

    /**
     * Check if controller has implemented the getFlashMessages() method and
     * assign its return to the flash messages property used for assertions.
     */
    private function checkFlashMessages()
    {
        if (!method_exists($this->controller, 'getFlashMessages')) {
            $this->fail(
                'Controller does not uses the FlashMessagesMethods or has a ' .
                'getFlashMessages() method for this test to be able to ' .
                'verify the session messages.'
            );
        }
        $this->flashMessages = $this->controller->getFlashMessages();
    }

    /**
     * Check if a view name is equals to expected
     *
     * @param string $expected
     * @param string $message
     */
    protected function assertViewEquals($expected, $message = '')
    {
        $view = $this->controller->getRequest()->getAttribute('template', null);
        $this->assertEquals($expected, $view, $message);
    }

    /**
     * Assert that an error message was set in the flash messages
     *
     * @param $expected
     * @param string $message
     */
    protected function assertErrorFlashMessageMatch($expected, $message = '')
    {
        $message = $message === ''
            ? "Expected error message '{$expected}' not set."
            : $message;
        $allMessages = $this->getFlashMessages()->get();
        $found = false;
        $messages = array_key_exists(FlashMessages::TYPE_ERROR, $allMessages)
            ? $allMessages[FlashMessages::TYPE_ERROR]
            : [];
        foreach ($messages as $msgToCheck) {
            if (preg_match("/.*{$expected}.*/i", $msgToCheck)) {
                $found = true;
                break;
            }
        }

        if (!$found) {
            $this->fail($message);
        }
        $this->assertTrue(true);
    }
}
