<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Domain;

use Slick\Orm\Annotations as Orm;
use Slick\Users\Tests\Shared\Domain\AuditAwareEntity;

/**
 * Account
 *
 * @package Slick\Users\Domain
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property string $name
 * @property string $email
 * @property bool   $active
 * @property bool   $confirmed
 *
 * @property Credential $credential
 *
 * @method bool isActive()
 * @method bool isConfirmed()
 *
 * @adapter slickUsers
 */
class Account extends AuditAwareEntity
{

    /**
     * @readwrite
     * @Orm\Column type=text, size=tiny
     * @var string
     */
    protected $name;

    /**
     * @readwrite
     * @Orm\Column type=text, length=128
     * @var string
     */
    protected $email;

    /**
     * @readwrite
     * @Orm\Column type=boolean
     * @var string
     */
    protected $active = true;

    /**
     * @readwrite
     * @Orm\Column type=boolean
     * @var string
     */
    protected $confirmed = 0;

    /**
     * @readwrite
     * @Orm\HasOne Slick\Users\Domain\Credential, lazyLoaded=true
     * @var Credential
     */
    protected $credential;
}
