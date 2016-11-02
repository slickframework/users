<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Controller;

use Aura\Router\Route;
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
     * Asserts that controller has a variable that references a provided object
     *
     * @param mixed  $expected
     * @param string $name
     * @param string $message
     */
    public function assertVarSame($expected, $name, $message = '')
    {
        $this->assertTrue(
            array_key_exists($name, $this->controller->getViewVars()),
            'Controller does not have a view variable named '.$name
        );
        $this->assertSame(
            $expected,
            $this->controller->getViewVars()[$name],
            $message
        );
    }

    /**
     * Assert that last call to redirect result in a response with
     * provided location header value.
     *
     * @param string $location
     * @param string $message
     */
    public function assertRedirectTo($location, $message = '')
    {
        $this->assertTrue(
            $this->controller->getResponse()->hasHeader('location'),
            "There was no call to Controller::redirect() method."
        );
        $header = $this->controller->getResponse()->getHeader('location');
        $this->assertEquals($location, $header[0], $message);
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
        $this->assertFlashMessage($expected, FlashMessages::TYPE_ERROR, $message);
    }

    /**
     * Assert that a success message was set in the flash messages
     *
     * @param $expected
     * @param string $message
     */
    protected function assertSuccessFlashMessageMatch($expected, $message = '')
    {
        $this->assertFlashMessage($expected, FlashMessages::TYPE_SUCCESS, $message);
    }

    /**
     * Assert that a message of provided type was set in the flash messages
     *
     * @param string $expected
     * @param int $type
     * @param string $message
     */
    protected function assertFlashMessage(
        $expected,
        $type = FlashMessages::TYPE_ERROR,
        $message = ''
    ) {
        $message = $message === ''
            ? "Expected message '{$expected}' not set."
            : $message;
        $allMessages = $this->getFlashMessages()->get();
        $found = false;
        $messages = array_key_exists($type, $allMessages)
            ? $allMessages[$type]
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

    /**
     * Set route attributes
     *
     * @param array $values
     */
    protected function setRouteAttributes(array $values)
    {
        $route = new Route();
        $route->attributes($values);
        $request = $this->controller->getRequest()
            ->withAttribute('route', $route);
        $response = $this->controller->getResponse();
        $this->controller->register($request, $response);
    }
}
