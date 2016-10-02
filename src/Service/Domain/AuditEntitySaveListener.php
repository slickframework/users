<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service\Domain;

use League\Event\AbstractListener;
use League\Event\EventInterface;
use League\Event\ListenerInterface;
use Slick\Orm\EntityInterface;
use Slick\Orm\Event\Save;
use Slick\Users\Shared\Utility\CurrentDateAwareInterface;
use Slick\Users\Shared\Utility\CurrentDateAwareMethods;
use Slick\Users\Tests\Shared\Domain\AuditAwareEntity;

/**
 * Audit Entity Save Listener
 *
 * This listener is responsible to set the updated field on AuditAwareEntity
 * entities before the get saved.
 *
 * @package Slick\Users\Service\Domain
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class AuditEntitySaveListener extends AbstractListener implements
    ListenerInterface,
    CurrentDateAwareInterface
{
    /**
     * For current date retrieval
     */
    use CurrentDateAwareMethods;

    /**
     * Handle an event.
     *
     * @param EventInterface $event
     *
     * @return void
     */
    public function handle(EventInterface $event)
    {
        if (
            $event instanceof Save &&
            $event->getName() == Save::ACTION_BEFORE_UPDATE
        ) {
            $this->updateChangeDate($event->getEntity());
        }
    }

    /**
     * Set the updated field to the current date if entity is an AuditAwareEntity
     *
     * @param EntityInterface $entity
     */
    protected function updateChangeDate(EntityInterface $entity)
    {
        if ($entity instanceof AuditAwareEntity) {
            $entity->setUpdated($this->getCurrentDate());
        }
    }
}
