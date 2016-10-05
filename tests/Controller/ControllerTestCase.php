<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Controller;

use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_TestCase as TestCase;
use Slick\Mvc\Controller;
use Slick\Mvc\Http\FlashMessages;

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
