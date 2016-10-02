<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Shared\Domain;

use Slick\Orm\Annotations as Orm;
use Slick\Orm\Entity;
use Slick\Orm\EntityInterface;

/**
 * Abstract Entity
 *
 * @package Slick\Users\Shared\Domain
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property string|int $id
 */
abstract class AbstractEntity extends Entity
{
    /**
     * @readwrite
     * @Orm\Column type=integer, size=big, autoIncrement, primaryKey
     * @var mixed
     */
    protected $id;

    /**
     * Returns entity ID
     *
     * This is usually the primary key or a UUID
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets entity ID
     *
     * @param mixed $entityId Primary key or a UUID
     *
     * @return self|$this|EntityInterface
     */
    public function setId($entityId)
    {
        $this->id = $entityId;
        return $this;
    }
}
