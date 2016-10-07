<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Shared\Configuration;

use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_TestCase as TestCase;
use Slick\Configuration\ConfigurationInterface;
use Slick\Users\Shared\Configuration\SettingsAwareMethods;

/**
 * Settings Aware Methods Trait Test
 *
 * @package Slick\Users\Tests\Shared\Configuration
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class SettingsAwareMethodsTraitTest extends TestCase
{

    use SettingsAwareMethods;

    public function testSettingsRetrieval()
    {
        $settings = $this->getSettings();
        $this->assertInstanceOf(ConfigurationInterface::class, $settings);
    }
}
