<?php

/**
 * UMC
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 *
 * @copyright Marius Strajeru
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 * @author    Marius Strajeru <ultimate.module.creator@gmail.com>
 *
 */

declare(strict_types=1);

namespace App\Umc\CoreBundle\Tests\Unit\Model;

use App\Umc\CoreBundle\Model\Entity;
use App\Umc\CoreBundle\Model\Module;
use App\Umc\CoreBundle\Model\Relation;
use App\Umc\CoreBundle\Util\StringUtil;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RelationTest extends TestCase
{
    /**
     * @var Module | MockObject
     */
    private $module;
    /**
     * @var StringUtil | MockObject
     */
    private $stringUtil;
    /**
     * @var Entity | MockObject
     */
    private $entityOneInstance;
    /**
     * @var Entity | MockObject
     */
    private $entityTwoInstance;

    protected function setUp(): void
    {
        $this->module = $this->createMock(Module::class);
        $this->stringUtil = $this->createMock(StringUtil::class);
        $this->entityOneInstance = $this->createMock(Entity::class);
        $this->entityTwoInstance = $this->createMock(Entity::class);
        $this->entityOneInstance->method('getNameSingular')->willReturn('entity1');
        $this->entityTwoInstance->method('getNameSingular')->willReturn('entity2');
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Relation::getRelatedEntity
     * @covers \App\Umc\CoreBundle\Model\Relation::getEntity
     * @covers \App\Umc\CoreBundle\Model\Relation::getEntityOneInstance
     * @covers \App\Umc\CoreBundle\Model\Relation::getEntityTwoInstance
     * @covers \App\Umc\CoreBundle\Model\Relation::__construct
     */
    public function testGetRelatedEntity()
    {
        $this->module->method('getEntities')->willReturn([$this->entityOneInstance, $this->entityTwoInstance]);
        $relation = $this->getInstance(['entity_one' => 'entity1', 'entity_two' => 'entity2']);
        $this->assertEquals($this->entityTwoInstance, $relation->getRelatedEntity($this->entityOneInstance));
        $this->assertEquals($this->entityOneInstance, $relation->getRelatedEntity($this->entityTwoInstance));
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Relation::getRelatedEntity
     * @covers \App\Umc\CoreBundle\Model\Relation::getEntity
     * @covers \App\Umc\CoreBundle\Model\Relation::__construct
     */
    public function testGetRelatedEntityWithError()
    {
        $dummyEntity = $this->createMock(Entity::class);
        $dummyEntity->method('getNameSingular')->willReturn('dummy');
        $this->module->method('getEntities')->willReturn([
            $this->entityOneInstance,
            $this->entityTwoInstance,
            $dummyEntity
        ]);
        $relation = $this->getInstance(['entity_one' => 'entity1', 'entity_two' => 'entity2']);
        $this->expectException(\InvalidArgumentException::class);
        $relation->getRelatedEntity($dummyEntity);
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Relation::getRelatedEntityFrontend
     * @covers \App\Umc\CoreBundle\Model\Relation::getEntity
     * @covers \App\Umc\CoreBundle\Model\Relation::getEntityOneInstance
     * @covers \App\Umc\CoreBundle\Model\Relation::getEntityTwoInstance
     * @covers \App\Umc\CoreBundle\Model\Relation::__construct
     */
    public function testGetRelatedEntityFrontend()
    {
        $this->module->method('getEntities')->willReturn([$this->entityOneInstance, $this->entityTwoInstance]);
        $relation = $this->getInstance([
            'entity_one' => 'entity1',
            'entity_two' => 'entity2',
            'entity_one_frontend' => true,
            'entity_two_frontend' => false,
        ]);
        $this->assertTrue($relation->getRelatedEntityFrontend($this->entityTwoInstance));
        $this->assertFalse($relation->getRelatedEntityFrontend($this->entityOneInstance));
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Relation::getRelatedEntityFrontend
     * @covers \App\Umc\CoreBundle\Model\Relation::getEntity
     * @covers \App\Umc\CoreBundle\Model\Relation::__construct
     */
    public function testGetRelatedEntityFrontendWithError()
    {
        $dummyEntity = $this->createMock(Entity::class);
        $dummyEntity->method('getNameSingular')->willReturn('dummy');
        $this->module->method('getEntities')->willReturn([
            $this->entityOneInstance,
            $this->entityTwoInstance,
            $dummyEntity
        ]);
        $relation = $this->getInstance(['entity_one' => 'entity1', 'entity_two' => 'entity2']);
        $this->expectException(\InvalidArgumentException::class);
        $relation->getRelatedEntityFrontend($dummyEntity);
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Relation::getRelatedEntityLabel
     * @covers \App\Umc\CoreBundle\Model\Relation::getEntity
     * @covers \App\Umc\CoreBundle\Model\Relation::getEntityOneInstance
     * @covers \App\Umc\CoreBundle\Model\Relation::getEntityOneLabel
     * @covers \App\Umc\CoreBundle\Model\Relation::getEntityTwoInstance
     * @covers \App\Umc\CoreBundle\Model\Relation::getEntityTwoLabel
     * @covers \App\Umc\CoreBundle\Model\Relation::__construct
     */
    public function testGetRelatedEntityLabel()
    {
        $this->module->method('getEntities')->willReturn([$this->entityOneInstance, $this->entityTwoInstance]);
        $relation = $this->getInstance([
            'entity_one' => 'entity1',
            'entity_two' => 'entity2',
            'entity_one_label' => 'label1',
            'entity_two_label' => 'label2',
        ]);
        $this->assertEquals('label2', $relation->getRelatedEntityLabel($this->entityOneInstance));
        $this->assertEquals('label1', $relation->getRelatedEntityLabel($this->entityTwoInstance));
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Relation::getRelatedEntityLabel
     * @covers \App\Umc\CoreBundle\Model\Relation::getEntity
     * @covers \App\Umc\CoreBundle\Model\Relation::getEntityOneInstance
     * @covers \App\Umc\CoreBundle\Model\Relation::getEntityOneLabel
     * @covers \App\Umc\CoreBundle\Model\Relation::getEntityTwoInstance
     * @covers \App\Umc\CoreBundle\Model\Relation::getEntityTwoLabel
     * @covers \App\Umc\CoreBundle\Model\Relation::__construct
     */
    public function testGetRelatedEntityLabelWthError()
    {
        $dummy = $this->createMock(Entity::class);
        $dummy->method('getNameSingular')->willReturn('dummy');
        $this->module->method('getEntities')->willReturn([$this->entityOneInstance, $this->entityTwoInstance, $dummy]);
        $relation = $this->getInstance([
            'entity_one' => 'entity1',
            'entity_two' => 'entity2',
            'entity_one_label' => 'label1',
            'entity_two_label' => 'label2',
        ]);
        $this->expectException(\InvalidArgumentException::class);
        $relation->getRelatedEntityLabel($dummy);
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Relation::getRelatedEntityPk
     * @covers \App\Umc\CoreBundle\Model\Relation::getEntity
     * @covers \App\Umc\CoreBundle\Model\Relation::getEntityOneInstance
     * @covers \App\Umc\CoreBundle\Model\Relation::getEntityTwoInstance
     * @covers \App\Umc\CoreBundle\Model\Relation::__construct
     */
    public function testGetRelatedEntityPk()
    {
        $this->module->method('getEntities')->willReturn([$this->entityOneInstance, $this->entityTwoInstance]);
        $this->entityOneInstance->method('getPk')->willReturn('pk1');
        $this->entityTwoInstance->method('getPk')->willReturn('pk2');
        $relation = $this->getInstance([
            'entity_one' => 'entity1',
            'entity_two' => 'entity2',
            'code' => 'code',
        ]);
        $this->stringUtil->method('snake')->willReturnArgument(0);
        $this->assertEquals('code_pk1', $relation->getRelatedEntityPk($this->entityTwoInstance));
        $this->assertEquals('code_pk2', $relation->getRelatedEntityPk($this->entityOneInstance));
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Relation::getRelatedEntityName
     * @covers \App\Umc\CoreBundle\Model\Relation::getEntity
     * @covers \App\Umc\CoreBundle\Model\Relation::getEntityOneInstance
     * @covers \App\Umc\CoreBundle\Model\Relation::getEntityTwoInstance
     * @covers \App\Umc\CoreBundle\Model\Relation::__construct
     */
    public function testGetRelatedEntityName()
    {
        $this->module->method('getEntities')->willReturn([$this->entityOneInstance, $this->entityTwoInstance]);
        $relation = $this->getInstance([
            'entity_one' => 'entity1',
            'entity_two' => 'entity2',
            'code' => 'code',
        ]);
        $this->stringUtil->method('snake')->willReturnArgument(0);
        $this->assertEquals('code_entity1', $relation->getRelatedEntityName($this->entityTwoInstance));
        $this->assertEquals('code_entity2', $relation->getRelatedEntityName($this->entityOneInstance));
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Relation::getModule
     * @covers \App\Umc\CoreBundle\Model\Relation::__construct
     */
    public function testGetModule()
    {
        $this->assertEquals($this->module, $this->getInstance([])->getModule());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Relation::getLabel
     * @covers \App\Umc\CoreBundle\Model\Relation::__construct
     */
    public function testGetLabel()
    {
        $this->assertEquals('label', $this->getInstance(['label' => 'label'])->getLabel());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Relation::getType
     * @covers \App\Umc\CoreBundle\Model\Relation::__construct
     */
    public function testGetType()
    {
        $this->assertEquals('type', $this->getInstance(['type' => 'type'])->getType());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Relation::toArray
     * @covers \App\Umc\CoreBundle\Model\Relation::__construct
     */
    public function testToArray()
    {
        $result = $this->getInstance([])->toArray();
        $this->assertArrayHasKey('code', $result);
        $this->assertArrayHasKey('label', $result);
        $this->assertArrayHasKey('entity_one', $result);
        $this->assertArrayHasKey('entity_one_label', $result);
        $this->assertArrayHasKey('entity_one_frontend', $result);
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Relation::getEntityOne
     * @covers \App\Umc\CoreBundle\Model\Relation::__construct
     */
    public function testGetEntityOne()
    {
        $relation = $this->getInstance([
            'entity_one' => 'entity1',
            'entity_two' => 'entity2',
            'code' => 'code',
        ]);
        $this->assertEquals('entity1', $relation->getEntityOne());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Relation::getEntityTwo
     * @covers \App\Umc\CoreBundle\Model\Relation::__construct
     */
    public function testGetEntityTwo()
    {
        $relation = $this->getInstance([
            'entity_one' => 'entity1',
            'entity_two' => 'entity2',
            'code' => 'code',
        ]);
        $this->assertEquals('entity2', $relation->getEntityTwo());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Relation::getEntityOneLabel
     * @covers \App\Umc\CoreBundle\Model\Relation::__construct
     */
    public function testGetEntityOneLabel()
    {
        $this->module->method('getEntities')->willReturn([$this->entityOneInstance, $this->entityTwoInstance]);
        $relation = $this->getInstance([
            'entity_one' => 'entity1',
            'entity_two' => 'entity2',
            'entity_one_label' => 'label',
        ]);
        $this->assertEquals('label', $relation->getEntityOneLabel());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Relation::getEntityOneLabel
     * @covers \App\Umc\CoreBundle\Model\Relation::__construct
     */
    public function testGetEntityOneLabelDefault()
    {
        $this->module->method('getEntities')->willReturn([$this->entityOneInstance, $this->entityTwoInstance]);
        $relation = $this->getInstance([
            'entity_one' => 'entity1',
            'entity_two' => 'entity2',
            'type' => 'parent'
        ]);
        $this->entityOneInstance->method('getLabelSingular')->willReturn('label_singular');
        $this->assertEquals('label_singular', $relation->getEntityOneLabel());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Relation::getEntityTwoLabel
     * @covers \App\Umc\CoreBundle\Model\Relation::__construct
     */
    public function testGetEntityTwoLabel()
    {
        $this->module->method('getEntities')->willReturn([$this->entityOneInstance, $this->entityTwoInstance]);
        $relation = $this->getInstance([
            'entity_one' => 'entity1',
            'entity_two' => 'entity2',
            'entity_two_label' => 'label',
        ]);
        $this->assertEquals('label', $relation->getEntityTwoLabel());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Relation::getEntityTwoLabel
     * @covers \App\Umc\CoreBundle\Model\Relation::__construct
     */
    public function testGetEntityTwoLabelDefault()
    {
        $this->module->method('getEntities')->willReturn([$this->entityOneInstance, $this->entityTwoInstance]);
        $relation = $this->getInstance([
            'entity_one' => 'entity1',
            'entity_two' => 'entity2',
            'type' => 'parent'
        ]);
        $this->entityTwoInstance->method('getLabelSingular')->willReturn('label_singular');
        $this->assertEquals('label_singular', $relation->getEntityTwoLabel());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Relation::getCode
     * @covers \App\Umc\CoreBundle\Model\Relation::__construct
     */
    public function testGetCode()
    {
        $this->assertEquals('', $this->getInstance([])->getCode());
        $this->assertEquals('code_', $this->getInstance(['code' => 'code'])->getCode());
        $this->assertEquals('code_', $this->getInstance(['code' => 'code_'])->getCode());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Relation::isEntityOneFrontend
     * @covers \App\Umc\CoreBundle\Model\Relation::__construct
     */
    public function testisEntityOneFrontend()
    {
        $this->assertTrue($this->getInstance(['entity_one_frontend' => true])->isEntityOneFrontend());
        $this->assertFalse($this->getInstance([])->isEntityOneFrontend());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Relation::isEntityTwoFrontend
     * @covers \App\Umc\CoreBundle\Model\Relation::__construct
     */
    public function testisEntityTwoFrontend()
    {
        $this->assertTrue($this->getInstance(['entity_two_frontend' => true])->isEntityTwoFrontend());
        $this->assertFalse($this->getInstance([])->isEntityTwoFrontend());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Relation::getHash
     * @covers \App\Umc\CoreBundle\Model\Relation::getEntity
     * @covers \App\Umc\CoreBundle\Model\Relation::getEntityOneInstance
     * @covers \App\Umc\CoreBundle\Model\Relation::getEntityTwoInstance
     * @covers \App\Umc\CoreBundle\Model\Relation::getCode
     * @covers \App\Umc\CoreBundle\Model\Relation::__construct
     */
    public function testGetHash()
    {
        $this->module->method('getEntities')->willReturn([$this->entityOneInstance, $this->entityTwoInstance]);
        $this->entityOneInstance->method('getNameSingular')->willReturn('entity1');
        $this->entityTwoInstance->method('getNameSingular')->willReturn('entity2');
        $relation = $this->getInstance(['entity_one' => 'entity1', 'entity_two' => 'entity2', 'code' => 'code']);
        $this->assertEquals('entity1##entity2##code_', $relation->getHash());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Relation::getEntity
     * @covers \App\Umc\CoreBundle\Model\Relation::__construct
     */
    public function testGetEntity()
    {
        $this->module->method('getEntities')->willReturn([$this->entityOneInstance, $this->entityTwoInstance]);
        $relation = $this->getInstance([
            'entity_one' => 'entity1',
            'entity_two' => 'entity2',
            'type' => 'parent'
        ]);
        $this->expectException(\InvalidArgumentException::class);
        $relation->getEntity('dummy');
    }

    /**
     * @param array $data
     * @return Relation
     */
    private function getInstance(array $data): Relation
    {
        return new Relation($this->module, $this->stringUtil, $data);
    }
}
