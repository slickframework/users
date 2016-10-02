<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Service\Domain;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Orm\Event\Save;
use Slick\Users\Service\Domain\AuditEntitySaveListener;
use Slick\Users\Shared\DataType\DateTime;
use Slick\Users\Tests\Shared\Domain\AuditAwareEntity;

/**
 * Audit Entity Save Listener Test
 *
 * @package Slick\Users\Tests\Service\Domain
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class AuditEntitySaveListenerTest extends TestCase
{

    /**
     * Should set the updated field on target entity
     * @test
     */
    public function setUpdatedDate()
    {
        /** @var AuditAwareEntity $entity */
        $entity = $this->getMockForAbstractClass(AuditAwareEntity::class);
        $event = new Save($entity);
        $event->setAction(Save::ACTION_BEFORE_UPDATE);
        $listener = new AuditEntitySaveListener();
        $now = new DateTime();
        $listener->setCurrentDate($now)->handle($event);
        $this->assertSame($now, $entity->updated);
    }
}
