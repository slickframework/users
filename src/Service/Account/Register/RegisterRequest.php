<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service\Account\Register;

use Slick\Common\Base;
use Slick\Users\Service\Account\PasswordEncryptionInterface;
use Slick\Users\Service\Account\PasswordEncryptionService;
use Slick\Users\Shared\Di\DependencyContainerAwareInterface;
use Slick\Users\Shared\Di\DependencyContainerAwareMethods;

/**
 * Account Register Request
 *
 * @package Slick\Users\Service\Account\Register
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property string $name
 * @property string $email
 *
 * @property PasswordEncryptionInterface $password
 */
class RegisterRequest extends Base implements DependencyContainerAwareInterface
{

    /**
     * @read
     * @var string
     */
    protected $name;

    /**
     * @read
     * @var string
     */
    protected $email;

    /**
     * @read
     * @var string|PasswordEncryptionInterface
     */
    protected $password;

    /**
     * @var PasswordEncryptionInterface
     */
    protected $encryptionService;

    /**
     * Used to access dependency container
     */
    use DependencyContainerAwareMethods;

    /**
     * Account Register Request
     *
     * @param string      $email
     * @param string      $password
     * @param null|string $name
     */
    public function __construct($email, $password, $name = null)
    {
        parent::__construct([]);
        $this->email = $email;
        $this->password = $password;
        $this->name = $name;
    }

    /**
     * Gets the password encrypted
     *
     * @return PasswordEncryptionInterface
     */
    public function getPassword()
    {
        if (! $this->password instanceof PasswordEncryptionService) {
            $this->password = $this->getEncryptionService()->setPassword($this->password);
        }
        return $this->password;
    }

    /**
     * Get encryption service
     *
     * @return PasswordEncryptionInterface
     */
    public function getEncryptionService()
    {
        if (!$this->encryptionService) {
            $this->setEncryptionService(
                $this->getContainer()->get('passwordEncryptionService')
            );
        }

        return $this->encryptionService;
    }

    /**
     * Set encryption service
     *
     * @param PasswordEncryptionInterface $encryptionService
     *
     * @return RegisterRequest
     */
    public function setEncryptionService(
        PasswordEncryptionInterface $encryptionService
    ) {
        $this->encryptionService = $encryptionService;
        return $this;
    }
}
