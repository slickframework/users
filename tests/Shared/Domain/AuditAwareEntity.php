<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Shared\Domain;

use Slick\Orm\Annotations as Orm;
use Slick\Users\Shared\DataType\DateTime;
use Slick\Users\Shared\Domain\AbstractEntity;
use Slick\Users\Shared\Utility\CurrentDateAwareInterface;
use Slick\Users\Shared\Utility\CurrentDateAwareMethods;

/**
 * Audit Aware Entity
 *
 * This entity manages the date created, date updated and last modified
 * fields so that it can return the correspondent objects for raw data
 * and create/updated them on entity save().
 *
 * @package Slick\Users\Tests\Shared\Domain
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property DateTime $created
 * @property DateTime $updated
 */
abstract class AuditAwareEntity extends AbstractEntity implements
    CurrentDateAwareInterface
{

    /**
     * @readwrite
     * @Orm\Column type=datetime
     * @var DateTime
     */
    protected $created;

    /**
     * @readwrite
     * @Orm\Column type=datetime
     * @var DateTime
     */
    protected $updated;

    /**
     * @readwrite
     * @Orm\Column type=integer, size=big
     * @var int
     */
    protected $lastModifiedBy = 1;

    /**
     * For current date retrieval
     */
    use CurrentDateAwareMethods;

    protected $author;

    /**
     * Gets created property
     *
     * @return DateTime
     */
    public function getCreated()
    {
        if (!$this->created) {
            $this->setCreated($this->getCurrentDate());
        }
        return $this->created;
    }

    /**
     * Sets created property
     *
     * @param DateTime|string|int $created
     *
     * @return AuditAwareEntity
     */
    public function setCreated($created)
    {
        $this->created = ($created instanceof DateTime)
            ? $created
            : new DateTime($created);
        return $this;
    }

    /**
     * Gets updated property
     *
     * @return DateTime
     */
    public function getUpdated()
    {
        if (!$this->updated) {
            $this->setUpdated($this->getCurrentDate());
        }
        return $this->updated;
    }

    /**
     * Sets updated property
     *
     * @param DateTime|string|int $updated
     *
     * @return AuditAwareEntity
     */
    public function setUpdated($updated)
    {
        $this->updated = ($updated instanceof DateTime)
            ? $updated
            : new DateTime($updated);
        return $this;
    }

}
