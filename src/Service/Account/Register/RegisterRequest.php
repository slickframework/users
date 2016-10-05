<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service\Account\Register;

use Slick\Common\Base;
use Slick\Users\Service\Account\PasswordEncryptionService;

/**
 * Account Register Request
 *
 * @package Slick\Users\Service\Account\Register
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property string $name
 * @property string $email
 *
 * @property PasswordEncryptionService $password
 */
class RegisterRequest extends Base
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
     * @var string
     */
    protected $password;

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
     * @return PasswordEncryptionService
     */
    public function getPassword()
    {
        if (! $this->password instanceof PasswordEncryptionService) {
            $this->password = new PasswordEncryptionService($this->password);
        }
        return $this->password;
    }
}
