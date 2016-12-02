<?php

/**
 * This file is part of slick/users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Service\Account;

use Psr\Log\LoggerInterface;
use Slick\Users\Domain\Account;
use Slick\Users\Service\Account\PictureUpdaterService;
use Slick\Users\Service\Authentication;
use Slick\Users\Tests\TestCase;

/**
 * Picture Updater Service Test Case
 *
 * @package Slick\Users\Tests\Service\Account
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class PictureUpdaterServiceTest extends TestCase
{

    /**
     * @var PictureUpdaterService
     */
    protected $service;

    /**
     * Sets the SUT updater service
     */
    protected function setUp()
    {
        parent::setUp();
        $this->service = new PictureUpdaterService(
            \Phake::mock(LoggerInterface::class)
        );
    }

    /**
     * Should get the path under webroot
     * @test
     */
    public function testGetPicturePath()
    {
        $path = APP_PATH . DIRECTORY_SEPARATOR . PictureUpdaterService::PATH;
        $this->assertEquals($path, $this->service->getPath());
    }
}
