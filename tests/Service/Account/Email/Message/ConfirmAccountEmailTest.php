<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Service\Account\Email\Message;

use Slick\Configuration\ConfigurationInterface;
use Slick\Mail\MessageBody;
use Slick\Mail\MessageBodyInterface;
use Slick\Users\Domain\Account;
use Slick\Users\Domain\Token;
use Slick\Users\Service\Account\Email\Message\ConfirmAccountEmail;
use Slick\Users\Service\Account\Email\Message\EmailMessageInterface;
use Slick\Users\Service\Email\MessageBodyBuilderInterface;
use Slick\Users\Tests\TestCase;

/**
 * Confirm Account Email Test
 *
 * @package Slick\Users\Tests\Service\Account\Email\Message
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class ConfirmAccountEmailTest extends TestCase
{

    /**
     * @var ConfirmAccountEmail
     */
    private $message;

    /**
     * @var Account
     */
    private $account;

    private $accountData = [
        'id' => '12',
        'email' => 'jon.doe@example.com',
        'name' => 'Jon Doe'
    ];

    /**
     * Set the SUT mail message object
     */
    protected function setUp()
    {
        parent::setUp();
        $this->account = new Account($this->accountData);
        $this->message = new ConfirmAccountEmail($this->account);
    }

    /**
     * Should set the addressee to this message
     * @test
     */
    public function testEmailAddress()
    {
        $this->getMockedToken();
        $this->message->prepareMessage();
        $this->assertEquals(
            "{$this->account->name} <{$this->account->email}>",
            $this->message->getToAddressList()
        );
    }

    /**
     * Should have access to e-mail settings
     * @test
     */
    public function testSettingsAccess()
    {
        $settings = $this->message->getSettings();
        $this->assertInstanceOf(ConfigurationInterface::class, $settings);
    }

    /**
     * Get the from email address from settings
     * @test
     */
    public function testFromDefinitions()
    {
        $this->message->setSettings($this->getSettings());
        $this->getMockedToken();
        $this->message->prepareMessage();
        $this->assertEquals(
            "no-replay@example.com",
            $this->message->getFromAddressList()
        );
    }

    /**
     * Should use the subject from settings, translate it and replace
     * placeholders
     * @test
     */
    public function testSubject()
    {
        $this->getMockedToken();
        $this->message->setSettings($this->getSettings());
        $this->message->prepareMessage();
        $this->assertEquals(
            'Hello Jon Doe, confirm address jon.doe@example.com',
            $this->message->getSubject()
        );
    }

    public function testBodyBuilderLazyLoad()
    {
        $dependencies = $this->getDependencies();
        $builder = $dependencies['settingsMessageBuilder'];
        $container = $this->getContainerMock($dependencies);
        $this->message->setContainer($container);
        $this->assertSame($builder, $this->message->getMessageBuilder());
    }

    public function testBodyMessage()
    {
        $dependencies = $this->getDependencies();
        $builder = $dependencies['settingsMessageBuilder'];
        $token = $token = \Phake::partialMock(Token::class);
        \Phake::when($token)->save()->thenReturn(true);
        $this->message->setToken($token);

        $this->message->setMessageBuilder($builder);
        $this->message->prepareMessage();
        \Phake::verify($builder)->build($this->message, $this->anything());
        \Phake::verify($token)->save();
    }

    /**
     * Get a mocked token
     *
     * @return \Phake_IMock|Token
     */
    protected function getMockedToken()
    {
        $token = \Phake::partialMock(Token::class);
        \Phake::when($token)->save()->thenReturn(true);
        $this->message->setToken($token);
        return $token;
    }

    protected function getSettings()
    {
        $values = [
            'email.from' => 'no-replay@example.com',
            'email.subjects.confirmation' =>
                'Hello :name, confirm address :email',
            'email.messages.confirmation' => [
                'plain' => 'email/confirmation.plain.twig',
            ]
        ];
        return $this->getSettingsMock($values);
    }

    protected function getDependencies()
    {
        $builder = \Phake::mock(MessageBodyBuilderInterface::class);
        \Phake::when($builder)->set($this->isType('string'), $this->anything())->thenReturn($builder);
        return [
            'settingsMessageBuilder' => $builder
        ];
    }
}
