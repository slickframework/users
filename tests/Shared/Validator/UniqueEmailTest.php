<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Shared\Validator;

use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_TestCase as TestCase;
use Slick\Form\InputInterface;
use Slick\Mvc\Form\EntityForm;
use Slick\Orm\Repository\EntityRepository;
use Slick\Orm\Repository\QueryObject\QueryObjectInterface;
use Slick\Users\Shared\Validator\UniqueEmail;

class UniqueEmailTest extends TestCase
{
    /**
     * @var UniqueEmail
     */
    protected $validator;

    protected function setUp()
    {
        parent::setUp();
        $this->validator = new UniqueEmail();
    }

    /**
     * Should lazy load the repository from Orm registry
     * @test
     */
    public function getAccountRepository()
    {
        $repo = $this->validator->getAccountsRepository();
        $this->assertInstanceOf(EntityRepository::class, $repo);
    }

    /**
     * Should fail validation if query count is more then 1
     * @test
     */
    public function validatesFalse()
    {
        $query = $this->getQueryObjectMock(1);
        $query
            ->expects($this->atLeastOnce())
            ->method('where')
            ->with(['email = :email' => [':email' => 'test']])
            ->willReturn($query)
        ;
        $query
            ->expects($this->atLeastOnce())
            ->method('andWhere')
            ->with($this->isType('array'))
            ->willReturn($query)
        ;
        $repository = $this->getAccountRepositoryMock($query);
        $this->validator->setAccountsRepository($repository);
        $element = \Phake::mock(InputInterface::class);
        \Phake::when($element)->getValue()->thenReturn('123');
        $form = \Phake::mock(EntityForm::class);
        \Phake::when($form)->get('id')->thenReturn($element);
        $context = ['form' => $form];
        $this->assertFalse($this->validator->validates('test', $context));
        $this->assertFalse($this->validator->validates('test', ['id' => '232']));
    }

    /**
     * Should passes validation if query count is 0
     * @test
     */
    public function validatesTrue()
    {
        $query = $this->getQueryObjectMock(0);
        $query
            ->expects($this->once())
            ->method('where')
            ->with(['email = :email' => [':email' => 'test']])
            ->willReturn($query)
        ;
        $query
            ->expects($this->once())
            ->method('andWhere')
            ->with($this->isType('array'))
            ->willReturn($query)
        ;
        $repository = $this->getAccountRepositoryMock($query);
        $this->validator->setAccountsRepository($repository);
        $this->assertTrue($this->validator->validates('test'));
    }


    /**
     * Gets the repository mock
     *
     * @param QueryObjectInterface $queryObject
     *
     * @return MockObject|EntityRepository
     */
    protected function getAccountRepositoryMock(QueryObjectInterface $queryObject)
    {
        /** @var EntityRepository | MockObject $repository */
        $repository = $this->getMockedObject(EntityRepository::class);
        $repository->expects($this->atLeastOnce())
            ->method('find')
            ->willReturn($queryObject);
        return $repository;
    }

    /**
     * Returns a mock for query object that will return the provided count
     * value when count() method is called.
     *
     * @param int $count
     *
     * @return MockObject|QueryObjectInterface
     */
    protected function getQueryObjectMock($count = 0)
    {
        /** @var QueryObjectInterface|MockObject $query */
        $query = $this->getMockedObject(QueryObjectInterface::class);
        $query->expects($this->atLeastOnce())
            ->method('count')
            ->willReturn($count);
        return $query;
    }

    /**
     * Get a mock object for provided class name
     *
     * @param $className
     *
     * @return MockObject
     */
    protected function getMockedObject($className)
    {
        $methods = get_class_methods($className);
        $object = $this->getMockBuilder($className)
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();
        return $object;
    }
}
