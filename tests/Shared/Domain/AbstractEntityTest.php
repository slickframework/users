<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Shared\Domain;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Users\Shared\Domain\AbstractEntity;

/**
 * Abstract Entity Test Case
 *
 * @package Slick\Users\Tests\Shared\Domain
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class AbstractEntityTest extends TestCase
{
    /**
     * Easy ID getter and setter
     */
    public function testIdGetterSetter()
    {
        /** @var AbstractEntity $entity */
        $entity = $this->getMockForAbstractClass(AbstractEntity::class);
        $entity->id = 10;
        $this->assertEquals(10, $entity->getId());
    }
}
