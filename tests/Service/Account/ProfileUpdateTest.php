<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Service\Account;

use Slick\Users\Domain\Account;
use Slick\Users\Service\Account\ProfileUpdater;
use Slick\Users\Tests\TestCase;

/**
 * Profile Update Test Case
 *
 * @package Slick\Users\Tests\Service\Account
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class ProfileUpdateTest extends TestCase
{
    /**
     * @var ProfileUpdater
     */
    protected $service;

    /**
     * Set the SUT profile update service
     */
    protected function setUp()
    {
        parent::setUp();
        $this->service = new ProfileUpdater();
    }

    /**
     * Should run the account save method
     * @test
     */
    public function updateAnAccount()
    {
        $account = \Phake::mock(Account::class);
        $this->assertSame(
            $this->service,
            $this->service->update($account)
        );
        \Phake::verify($account)->save();

    }
}
