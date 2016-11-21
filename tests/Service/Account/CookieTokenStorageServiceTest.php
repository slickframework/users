<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Service\Account;

use Slick\Users\Service\Account\CookieTokenStorageService;
use Slick\Users\Tests\TestCase;

/**
 * Cookie Token Storage Service Test Case
 *
 * @package Slick\Users\Tests\Service\Account
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class CookieTokenStorageServiceTest extends TestCase
{

    /**
     * @var CookieTokenStorageService
     */
    protected $service;

    /**
     * Sets the SUT object
     */
    protected function setUp()
    {
        parent::setUp();
        $this->service = new CookieTokenStorageService();
    }
}
