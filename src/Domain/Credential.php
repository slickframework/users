<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Domain;

use Slick\Orm\Annotations as Orm;
use Slick\Users\Shared\Domain\AbstractEntity;

/**
 * Credential
 *
 * @package Slick\Users\Domain
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property string  $username
 * @property string  $email
 * @property string  $password
 * @property Account $account
 *
 * @adapter slickUsers
 */
class Credential extends AbstractEntity
{

    /**
     * @readwrite
     * @Orm\Column type=text, size=45
     * @var string
     */
    protected $username;

    /**
     * @readwrite
     * @Orm\Column type=text, size=128
     * @var string
     */
    protected $email;

    /**
     * @readwrite
     * @Orm\Column type=text, size=60
     * @var string
     */
    protected $password;

    /**
     * @readwrite
     * @Orm\BelongsTo Slick\Users\Domain\Account
     * @var Account
     */
    protected $account;
}
