<?php

/**
 *
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
use App\Umc\CoreBundle\Model\Entity\Factory;
use App\Umc\CoreBundle\Model\Relation\Factory as RelationFactory;
use App\Umc\CoreBundle\Model\Module;
use App\Umc\CoreBundle\Util\StringUtil;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ModuleTest extends TestCase
{
    /**
     * @var StringUtil | MockObject
     */
    private $stringUtil;
    /**
     * @var Factory | MockObject
     */
    private $entityFactory;
    /**
     * @var RelationFactory
     */
    private $relationFactory;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->stringUtil = $this->createMock(StringUtil::class);
        $this->entityFactory = $this->createMock(Factory::class);
        $this->relationFactory = $this->createMock(RelationFactory::class);
        $this->stringUtil->method('snake')->willReturnArgument(0);
        $this->stringUtil->method('camel')->willReturnArgument(0);
        $this->stringUtil->method('hyphen')->willReturnArgument(0);
        $this->stringUtil->method('ucfirst')->willReturnArgument(0);
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Module::getEntities
     * @covers \App\Umc\CoreBundle\Model\Module::__construct
     */
    public function testGetEntities()
    {
        $data = [
            '_entities' => [[], []]
        ];
        $this->entityFactory->expects($this->exactly(2))->method('create')->willReturn(
            $this->createMock(Entity::class)
        );
        $this->assertEquals(2, count($this->getInstance($data)->getEntities()));
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Module::getNamespace
     * @covers \App\Umc\CoreBundle\Model\Module::__construct
     */
    public function testGetNamespace()
    {
        $this->assertEquals('Namespace', $this->getInstance(['namespace' => 'Namespace'])->getNamespace());
        $this->assertEquals('', $this->getInstance([])->getNamespace());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Module::getModuleName
     * @covers \App\Umc\CoreBundle\Model\Module::__construct
     */
    public function testGetModuleName()
    {
        $this->assertEquals('ModuleName', $this->getInstance(['module_name' => 'ModuleName'])->getModuleName());
        $this->assertEquals('', $this->getInstance([])->getModuleName());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Module::getMenuParent
     * @covers \App\Umc\CoreBundle\Model\Module::__construct
     */
    public function testGetMenuParent()
    {
        $this->assertEquals('Parent', $this->getInstance(['menu_parent' => 'Parent'])->getMenuParent());
        $this->assertEquals('', $this->getInstance([])->getMenuParent());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Module::getSortOrder
     * @covers \App\Umc\CoreBundle\Model\Module::__construct
     */
    public function testGetSortOrder()
    {
        $this->assertEquals(10, $this->getInstance(['sort_order' => 10])->getSortOrder());
        $this->assertEquals(0, $this->getInstance([])->getSortOrder());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Module::getMenuText
     * @covers \App\Umc\CoreBundle\Model\Module::__construct
     */
    public function testGetMenuText()
    {
        $this->assertEquals('Menu', $this->getInstance(['menu_text' => 'Menu'])->getMenuText());
        $this->assertEquals('', $this->getInstance([])->getMenuText());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Module::getLicense
     * @covers \App\Umc\CoreBundle\Model\Module::__construct
     */
    public function testGetLicense()
    {
        $this->assertEquals('License', $this->getInstance(['license' => 'License'])->getLicense());
        $this->assertEquals('', $this->getInstance([])->getLicense());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Module::getFrontKey
     * @covers \App\Umc\CoreBundle\Model\Module::__construct
     */
    public function testGetFrontKey()
    {
        $this->assertEquals('front_key', $this->getInstance(['front_key' => 'front_key'])->getFrontKey());
        $this->assertEquals('md', $this->getInstance(['module_name' => 'md'])->getFrontKey());
        $this->assertEquals('', $this->getInstance([])->getFrontKey());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Module::getConfigTab
     * @covers \App\Umc\CoreBundle\Model\Module::__construct
     */
    public function testGetConfigTab()
    {
        $this->assertEquals('tab', $this->getInstance(['config_tab' => 'tab'])->getConfigTab());
        $this->assertEquals('', $this->getInstance([])->getLicense());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Module::getConfigTabPosition
     * @covers \App\Umc\CoreBundle\Model\Module::__construct
     */
    public function testGetConfigTabPosition()
    {
        $this->assertEquals(10, $this->getInstance(['config_tab_position' => 10])->getConfigTabPosition());
        $this->assertEquals(0, $this->getInstance([])->getConfigTabPosition());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Module::toArray
     * @covers \App\Umc\CoreBundle\Model\Module::__construct
     */
    public function testToArray()
    {
        $entity = $this->createMock(Entity::class);
        $entity->expects($this->once())->method('toArray');
        $this->entityFactory->expects($this->once())->method('create')->willReturn($entity);
        $result = $this->getInstance(['_entities' => [[]]])->toArray();
        $this->assertArrayHasKey('namespace', $result);
        $this->assertArrayHasKey('module_name', $result);
        $this->assertArrayHasKey('_entities', $result);
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Module::getExtensionName
     * @covers \App\Umc\CoreBundle\Model\Module::__construct
     */
    public function testGetExtensionName()
    {
        $this->assertEquals(
            'ns_md',
            $this->getInstance(['namespace' => 'ns', 'module_name' => 'md'])->getExtensionName()
        );
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Module::isFrontend
     * @covers \App\Umc\CoreBundle\Model\Module::initEntityCacheData
     * @covers \App\Umc\CoreBundle\Model\Module::__construct
     */
    public function testIsFrontend()
    {
        $this->assertFalse($this->getInstance([])->isFrontend());
        $this->assertTrue($this->getInstance(['frontend' => true])->isFrontend());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Module::getEntitiesWithFlag
     * @covers \App\Umc\CoreBundle\Model\Module::initEntityCacheData
     * @covers \App\Umc\CoreBundle\Model\Module::cacheEntityData
     * @covers \App\Umc\CoreBundle\Model\Module::cacheEntityData
     * @covers \App\Umc\CoreBundle\Model\Module::__construct
     */
    public function testGetEntitiesWithFlag()
    {
        $entity1 = $this->createMock(Entity::class);
        $entity1->expects($this->once())->method('getFlags')->willReturn(['flag1', 'flag2']);
        $entity2 = $this->createMock(Entity::class);
        $entity2->expects($this->once())->method('getFlags')->willReturn(['flag2', 'flag3']);
        $this->entityFactory->expects($this->exactly(2))->method('create')
            ->willReturnOnConsecutiveCalls($entity1, $entity2);
        $instance = $this->getInstance(['_entities' => [[], []]]);
        $this->assertEquals([$entity1], $instance->getEntitiesWithFlag('flag1'));
        $this->assertEquals([$entity1, $entity2], $instance->getEntitiesWithFlag('flag2'));
        $this->assertEquals([$entity2], $instance->getEntitiesWithFlag('flag3'));
        $this->assertEquals([], $instance->getEntitiesWithFlag('dummy'));
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Module::hasEntitiesWithFlag
     * @covers \App\Umc\CoreBundle\Model\Module::initEntityCacheData
     * @covers \App\Umc\CoreBundle\Model\Module::cacheEntityData
     * @covers \App\Umc\CoreBundle\Model\Module::cacheEntityData
     * @covers \App\Umc\CoreBundle\Model\Module::__construct
     */
    public function testHasEntitiesWithFlag()
    {
        $entity1 = $this->createMock(Entity::class);
        $entity1->expects($this->once())->method('getFlags')->willReturn(['flag0', 'flag2']);
        $entity2 = $this->createMock(Entity::class);
        $entity2->expects($this->once())->method('getFlags')->willReturn(['flag2', 'flag3']);
        $this->entityFactory->expects($this->exactly(2))->method('create')
            ->willReturnOnConsecutiveCalls($entity1, $entity2);
        $instance = $this->getInstance(['_entities' => [[], []]]);
        $this->assertTrue($instance->hasEntitiesWithFlag('flag0'));
        $this->assertTrue($instance->hasEntitiesWithFlag('flag2'));
        $this->assertTrue($instance->hasEntitiesWithFlag('flag3'));
        $this->assertFalse($instance->hasEntitiesWithFlag('dummy'));
    }

    /**
     * @param $data
     * @return Module
     */
    private function getInstance($data): Module
    {
        return new Module(
            $this->stringUtil,
            $this->entityFactory,
            $this->relationFactory,
            $data
        );
    }
}
