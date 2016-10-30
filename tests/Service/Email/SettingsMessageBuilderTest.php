<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Service\Email;

use Slick\Mail\MessageBodyInterface;
use Slick\Mail\MessageInterface;
use Slick\Mail\Mime\MimeMessage;
use Slick\Mail\Mime\MimePartInterface;
use Slick\Mail\Mime\MimeParts;
use Slick\Users\Service\Account\Email\Message\EmailMessageInterface;
use Slick\Users\Service\Email\SettingsMessageBuilder;
use Slick\Users\Tests\TestCase;

/**
 * Settings Message Builder Test Case
 *
 * @package Slick\Users\Tests\Service\Email
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class SettingsMessageBuilderTest extends TestCase
{
    /**
     * @var EmailMessageInterface|\Phake_IMock
     */
    protected $message;

    /**
     * @var array
     */
    protected $settings = [
        'html' => 'email/confirmation.html.twig',
        'plain' => 'email/confirmation.plain.twig',
        'embed' => [
            'logo' => [
                'image/png' => '/webroot/img/users-icon.png'
            ]
        ]
    ];

    public function testBuild()
    {
        $this->settings['embed']['logo']['image/png'] = APP_PATH .
            $this->settings['embed']['logo']['image/png'];
        $this->message = $this->getMockMessage();
        $builder = new SettingsMessageBuilder();
        $result = $builder->build($this->message, $this->settings);
        $this->assertSame($result, $builder);
        return $builder;
    }

    public function testSimpleMessage()
    {
        $message = \Phake::mock(MessageInterface::class);
        $settings = [
            'html' => 'email/confirmation.html.twig',
            'plain' => 'email/confirmation.plain.twig',
        ];
        $builder = new SettingsMessageBuilder();
        $builder->build($message, $settings);
        \Phake::verify($message)->setBody($this->isInstanceOf(MessageBodyInterface::class));
    }

    /**
     * @param SettingsMessageBuilder $builder
     *
     * @depends testBuild
     *
     * @return SettingsMessageBuilder
     */
    public function testVariablesSet(SettingsMessageBuilder $builder)
    {
        $result = $builder->set('hello', 'world');
        $this->assertSame($result, $builder);
        return $builder;
    }

    /**
     * @param SettingsMessageBuilder $builder
     *
     * @depends testVariablesSet
     */
    public function testHtmlPart(SettingsMessageBuilder $builder)
    {
        \Phake::verify($builder->getMessage()->parts(), \Phake::times(3))
            ->add(
                $this->isInstanceOf(MimePartInterface::class)
            )
        ;
    }

    protected function getMockMessage()
    {
        $message = \Phake::mock(MimeMessage::class);
        $parts = \Phake::mock(MimeParts::class);
        \Phake::when($message)->parts()->thenReturn($parts);
        return $message;
    }
}
