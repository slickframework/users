<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Shared\Domain;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Users\Shared\DataType\DateTime;

/**
 * Audit Aware Entity Test Case
 *
 * @package Slick\Users\Tests\Shared\Domain
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class AuditAwareEntityTest extends TestCase
{

    /**
     * @var AuditAwareEntity
     */
    protected $entity;

    /**
     * Set the SUT entity object
     */
    protected function setUp()
    {
        parent::setUp();
        $this->entity = $this->getMockForAbstractClass(
            AuditAwareEntity::class
        );
    }

    /**
     * Should return current date
     * @test
     */
    public function getCreatedDate()
    {
        $now = new DateTime();

        $date = $this->entity->setCurrentDate($now)->created;
        $this->assertSame($now, $date);
    }

    /**
     * Any value passed to the created should be converted to a DateTime object
     * @test
     */
    public function setCreatedConvertToDateTime()
    {
        $date = "2016-10-01 23:28:23";
        $created = $this->entity->setCreated($date)->created;
        $this->assertEquals($date, $created->format(DateTime::DEFAULT_FORMAT));
    }

    /**
     * Should return current date
     * @test
     */
    public function getUpdatedDate()
    {
        $now = new DateTime();

        $date = $this->entity->setCurrentDate($now)->updated;
        $this->assertSame($now, $date);
    }

    /**
     * Any value passed to the updated should be converted to a DateTime object
     * @test
     */
    public function setUpdatedConvertToDateTime()
    {
        $date = "2016-10-01 23:28:23";
        $updated = $this->entity->setUpdated($date)->updated;
        $this->assertEquals($date, $updated->format(DateTime::DEFAULT_FORMAT));
    }
}
