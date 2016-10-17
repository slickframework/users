<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Shared\Configuration;

use Slick\Configuration\Configuration;
use Slick\Configuration\ConfigurationInterface;

/**
 * Settings Aware Methods
 *
 * @package Slick\Users\Shared\Configuration
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
trait SettingsAwareMethods
{

    /**
     * @var ConfigurationInterface
     */
    protected $settings;

    /**
     * Get configuration settings
     *
     * @return ConfigurationInterface
     */
    public function getSettings()
    {
        if (!$this->settings) {
            $this->setSettings(Configuration::get('settings'));
        }
        return $this->settings;
    }

    /**
     * Set configuration settings
     *
     * @param ConfigurationInterface $settings
     *
     * @return self|SettingsAwareInterface
     */
    public function setSettings(ConfigurationInterface $settings)
    {
        $this->settings = $settings;
        return $this;
    }
}
