<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Service\Email;

use Slick\Mail\MessageInterface;
use Slick\Mail\Transport\PhpMailTransport;
use Slick\Users\Service\Email\SlickMailTransport;
use Slick\Users\Tests\TestCase;

/**
 * Slick Mail Transport Test Case
 *
 * @package Slick\Users\Tests\Service\Email
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class SlickMailTransportTest extends TestCase
{

    /**
     * @var SlickMailTransport
     */
    protected $transporter;

    protected function setUp()
    {
        parent::setUp();
        $this->transporter = new SlickMailTransport();
    }

    public function testCreateTransporter()
    {
        $this->transporter->setSettings($this->getSettingsMock());
        $transporter = $this->transporter->getTransporter();
        $this->assertInstanceOf(PhpMailTransport::class, $transporter);
    }

    public function testSendMessage()
    {
        $message = \Phake::mock(MessageInterface::class);
        $transporter = \Phake::mock(PhpMailTransport::class);
        $this->transporter->setTransporter($transporter);
        $this->transporter->send($message);
        \Phake::verify($transporter)->send($message);
    }
}
