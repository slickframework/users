<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Domain;

use Slick\Users\Domain\Token;
use Slick\Users\Shared\DataType\DateTime;
use Slick\Users\Tests\TestCase;

/**
 * Token Test Case
 *
 * @package Slick\Users\Tests\Domain
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class TokenTest extends TestCase
{

    /**
     * @var Token
     */
    protected $token;

    /**
     * Set the SUT token object
     */
    protected function setUp()
    {
        parent::setUp();
        $this->token = new Token(['id' => 2]);
    }

    public function testEntityImplementation()
    {
        $this->assertEquals(2, $this->token->getId());
    }

    public function testTtl()
    {
        $now = new DateTime();
        $this->assertTrue($now <= $this->token->getTtl());
    }

    public function testValidity()
    {
        $this->token->setTtl('-1 minute');
        $this->assertFalse($this->token->isValid());
    }

    public function testAsString()
    {
        $this->assertEquals((string)$this->token, $this->token->getPublicToken());
    }

    public function testTokenHash()
    {
        $code = 123123123;
        $this->token->code = $code;
        $this->assertEquals(hash('sha256', $code), $this->token->getToken());
    }
}
