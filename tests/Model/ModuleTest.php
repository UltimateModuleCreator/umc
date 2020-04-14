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

namespace App\Tests\Model;

use App\Model\Attribute;
use App\Model\Entity;
use App\Model\Entity\Factory;
use App\Model\Module;
use App\Service\License\ProcessorInterface;
use App\Util\StringUtil;
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
     * setup tests
     */
    protected function setUp()
    {
        $this->stringUtil = $this->createMock(StringUtil::class);
        $this->entityFactory = $this->createMock(Factory::class);
        $this->stringUtil->method('snake')->willReturnArgument(0);
        $this->stringUtil->method('camel')->willReturnArgument(0);
        $this->stringUtil->method('hyphen')->willReturnArgument(0);
        $this->stringUtil->method('ucfirst')->willReturnArgument(0);
    }


    /**
     * @covers \App\Model\Module::getEntities
     * @covers \App\Model\Module::__construct
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
     * @covers \App\Model\Module::getNamespace
     * @covers \App\Model\Module::__construct
     */
    public function testGetNamespace()
    {
        $this->assertEquals('Namespace', $this->getInstance(['namespace' => 'Namespace'])->getNamespace());
        $this->assertEquals('', $this->getInstance([])->getNamespace());
    }

    /**
     * @covers \App\Model\Module::getModuleName
     * @covers \App\Model\Module::__construct
     */
    public function testGetModuleName()
    {
        $this->assertEquals('ModuleName', $this->getInstance(['module_name' => 'ModuleName'])->getModuleName());
        $this->assertEquals('', $this->getInstance([])->getModuleName());
    }

    /**
     * @covers \App\Model\Module::getMenuParent
     * @covers \App\Model\Module::__construct
     */
    public function testGetMenuParent()
    {
        $this->assertEquals('Parent', $this->getInstance(['menu_parent' => 'Parent'])->getMenuParent());
        $this->assertEquals('', $this->getInstance([])->getMenuParent());
    }

    /**
     * @covers \App\Model\Module::getSortOrder
     * @covers \App\Model\Module::__construct
     */
    public function testGetSortOrder()
    {
        $this->assertEquals(10, $this->getInstance(['sort_order' => 10])->getSortOrder());
        $this->assertEquals(0, $this->getInstance([])->getSortOrder());
    }

    /**
     * @covers \App\Model\Module::getMenuText
     * @covers \App\Model\Module::__construct
     */
    public function testGetMenuText()
    {
        $this->assertEquals('Menu', $this->getInstance(['menu_text' => 'Menu'])->getMenuText());
        $this->assertEquals('', $this->getInstance([])->getMenuText());
    }

    /**
     * @covers \App\Model\Module::getLicense
     * @covers \App\Model\Module::__construct
     */
    public function testGetLicense()
    {
        $this->assertEquals('License', $this->getInstance(['license' => 'License'])->getLicense());
        $this->assertEquals('', $this->getInstance([])->getLicense());
    }

    /**
     * @covers \App\Model\Module::getFrontKey
     * @covers \App\Model\Module::__construct
     */
    public function testGetFrontKey()
    {
        $this->assertEquals('front_key', $this->getInstance(['front_key' => 'front_key'])->getFrontKey());
        $this->assertEquals('ns_md', $this->getInstance(['namespace' => 'ns', 'module_name' => 'md'])->getFrontKey());
        $this->assertEquals('_', $this->getInstance([])->getFrontKey());
    }

    /**
     * @covers \App\Model\Module::getConfigTab
     * @covers \App\Model\Module::__construct
     */
    public function testGetConfigTab()
    {
        $this->assertEquals('tab', $this->getInstance(['config_tab' => 'tab'])->getConfigTab());
        $this->assertEquals('', $this->getInstance([])->getLicense());
    }

    /**
     * @covers \App\Model\Module::getConfigTabPosition
     * @covers \App\Model\Module::__construct
     */
    public function testGetConfigTabPosition()
    {
        $this->assertEquals(10, $this->getInstance(['config_tab_position' => 10])->getConfigTabPosition());
        $this->assertEquals(0, $this->getInstance([])->getConfigTabPosition());
    }

    /**
     * @covers \App\Model\Module::isUmcCrud
     * @covers \App\Model\Module::__construct
     */
    public function testIsUmcCrud()
    {
        $this->assertTrue($this->getInstance(['umc_crud' => 1])->isUmcCrud());
        $this->assertFalse($this->getInstance([])->isUmcCrud());
    }

    /**
     * @covers \App\Model\Module::toArray
     * @covers \App\Model\Module::__construct
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
     * @covers \App\Model\Module::getFormattedLicense
     * @covers \App\Model\Module::__construct
     */
    public function testGetFormattedLicense()
    {
        $php = $this->createMock(ProcessorInterface::class);
        $xml = $this->createMock(ProcessorInterface::class);
        $php->expects($this->once())->method('process');
        $xml->expects($this->once())->method('process');
        $module = new Module($this->stringUtil, $this->entityFactory, ['php' => $php, 'xml' => $xml], []);
        $module->getFormattedLicense('php');
        $module->getFormattedLicense('xml');
    }

    /**
     * @covers \App\Model\Module::getFormattedLicense()
     * @covers \App\Model\Module::__construct()
     */
    public function testGetFormattedLicenseWithException()
    {
        $module = new Module($this->stringUtil, $this->entityFactory, ['wrong' => new \stdClass()], []);
        $this->expectException(\Exception::class);
        $module->getFormattedLicense('wrong');
    }

    /**
     * @covers \App\Model\Module::getFormattedLicense()
     * @covers \App\Model\Module::__construct()
     */
    public function testGetFormattedLicenseWithMIssing()
    {
        $module = $this->getInstance([]);
        $this->expectException(\Exception::class);
        $module->getFormattedLicense('missing');
    }

    /**
     * @covers \App\Model\Module::getExtensionName
     * @covers \App\Model\Module::__construct
     */
    public function testGetExtensionName()
    {
        $this->assertEquals(
            'ns_md',
            $this->getInstance(['namespace' => 'ns', 'module_name' => 'md'])->getExtensionName()
        );
    }

    /**
     * @covers \App\Model\Module::getComposerExtensionName
     * @covers \App\Model\Module::__construct
     */
    public function testGetComposerExtensionName()
    {
        $this->assertEquals(
            'ns/module-md',
            $this->getInstance(['namespace' => 'ns', 'module_name' => 'md'])->getComposerExtensionName()
        );
    }

    /**
     * @covers \App\Model\Module::getModuleDependencies
     * @covers \App\Model\Module::__construct
     */
    public function testGetModuleDependencies()
    {
        $entity1 = $this->createMock(Entity::class);
        $entity1->method('getModuleDependencies')->willReturn(['dep1', 'dep2']);
        $entity2 = $this->createMock(Entity::class);
        $entity2->method('getModuleDependencies')->willReturn(['dep1', 'dep3']);
        $this->entityFactory->expects($this->exactly(2))->method('create')
            ->willReturnOnConsecutiveCalls($entity1, $entity2);
        $data = [
            '_entities' => [
                ['data'],
                ['data']
            ],
            'umc_crud' => true
        ];
        $module = $this->getInstance($data);
        $this->assertTrue(in_array('Umc_Crud', $module->getModuleDependencies()));
        $this->assertTrue(in_array('dep1', $module->getModuleDependencies()));
        $this->assertTrue(in_array('dep2', $module->getModuleDependencies()));
        $this->assertTrue(in_array('dep3', $module->getModuleDependencies()));
    }

    /**
     * @covers \App\Model\Module::getModuleDependencies
     * @covers \App\Model\Module::__construct
     */
    public function testGetModuleDependenciesNoUmcCrud()
    {
        $entity1 = $this->createMock(Entity::class);
        $entity1->method('getModuleDependencies')->willReturn(['dep1', 'dep2']);
        $entity2 = $this->createMock(Entity::class);
        $entity2->method('getModuleDependencies')->willReturn(['dep1', 'dep3']);
        $this->entityFactory->expects($this->exactly(2))->method('create')
            ->willReturnOnConsecutiveCalls($entity1, $entity2);
        $data = [
            '_entities' => [
                ['data'],
                ['data']
            ],
            'umc_crud' => false
        ];
        $module = $this->getInstance($data);
        $this->assertFalse(in_array('Umc_Crud', $module->getModuleDependencies()));
        $this->assertTrue(in_array('dep1', $module->getModuleDependencies()));
        $this->assertTrue(in_array('dep2', $module->getModuleDependencies()));
        $this->assertTrue(in_array('dep3', $module->getModuleDependencies()));
    }

    /**
     * @covers \App\Model\Module::getComposerDependencies
     * @covers \App\Model\Module::__construct
     */
    public function testGetComposerDependencies()
    {
        $entity1 = $this->createMock(Entity::class);
        $entity1->method('getComposerDependencies')->willReturn(['dep1', 'dep2']);
        $entity2 = $this->createMock(Entity::class);
        $entity2->method('getComposerDependencies')->willReturn(['dep1', 'dep3']);
        $this->entityFactory->expects($this->exactly(2))->method('create')
            ->willReturnOnConsecutiveCalls($entity1, $entity2);
        $data = [
            '_entities' => [
                ['data'],
                ['data']
            ],
            'umc_crud' => true
        ];
        $module = $this->getInstance($data);
        $this->assertTrue(in_array('umc/module-crud', $module->getComposerDependencies()));
        $this->assertTrue(in_array('dep1', $module->getComposerDependencies()));
        $this->assertTrue(in_array('dep2', $module->getComposerDependencies()));
        $this->assertTrue(in_array('dep3', $module->getComposerDependencies()));
    }

    /**
     * @covers \App\Model\Module::getComposerDependencies
     * @covers \App\Model\Module::__construct
     */
    public function testGetComposerDependenciesNoUmcCrid()
    {
        $entity1 = $this->createMock(Entity::class);
        $entity1->method('getComposerDependencies')->willReturn(['dep1', 'dep2']);
        $entity2 = $this->createMock(Entity::class);
        $entity2->method('getComposerDependencies')->willReturn(['dep1', 'dep3']);
        $this->entityFactory->expects($this->exactly(2))->method('create')
            ->willReturnOnConsecutiveCalls($entity1, $entity2);
        $data = [
            '_entities' => [
                ['data'],
                ['data']
            ],
            'umc_crud' => false
        ];
        $module = $this->getInstance($data);
        $this->assertFalse(in_array('umc/module-crud', $module->getComposerDependencies()));
        $this->assertTrue(in_array('dep1', $module->getComposerDependencies()));
        $this->assertTrue(in_array('dep2', $module->getComposerDependencies()));
        $this->assertTrue(in_array('dep3', $module->getComposerDependencies()));
    }

    /**
     * @covers \App\Model\Module::getAclName
     * @covers \App\Model\Module::__construct
     */
    public function testGetAclName()
    {
        $expected = 'ns_md::md';
        $data = [
            'namespace' => 'ns',
            'module_name' => 'md'
        ];
        $this->assertEquals($expected, $this->getInstance($data)->getAclName());
    }

    /**
     * @covers \App\Model\Module::hasAttributeType
     * @covers \App\Model\Module::__construct
     */
    public function testHasAttributeType()
    {
        $entity = $this->createMock(Entity::class);
        $entity->method('hasAttributeType')->willReturnMap([
            ['text', true],
            ['select', false]
        ]);
        $this->entityFactory->expects($this->once())->method('create')->willReturn($entity);
        $data = [
            '_entities' => [
                ['data']
            ]
        ];
        $module = $this->getInstance($data);
        $this->assertTrue($module->hasAttributeType('text'));
        //call twice for memozing
        $this->assertTrue($module->hasAttributeType('text'));

        $this->assertFalse($module->hasAttributeType('select'));
        //call teice to test memozing
        $this->assertFalse($module->hasAttributeType('select'));
    }

    /**
     * @covers \App\Model\Module::hasSearchableEntities
     * @covers \App\Model\Module::initEntityCacheData
     * @covers \App\Model\Module::__construct
     */
    public function testHasSearchableEntities()
    {
        $entity = $this->createMock(Entity::class);
        $entity->method('isSearch')->willReturn(true);
        $this->entityFactory->expects($this->once())->method('create')->willReturn($entity);
        $data = [
            '_entities' => [
                ['data']
            ]
        ];
        $module = $this->getInstance($data);
        $this->assertTrue($module->hasSearchableEntities());
        //call twice to test memoizing
        $this->assertTrue($module->hasSearchableEntities());

        $this->assertFalse($this->getInstance([])->hasSearchableEntities());
    }

    /**
     * @covers \App\Model\Module::getSearchableEntities
     * @covers \App\Model\Module::initEntityCacheData
     * @covers \App\Model\Module::__construct
     */
    public function testGetSearchableEntities()
    {
        $entity = $this->createMock(Entity::class);
        $entity->method('isSearch')->willReturn(true);
        $this->entityFactory->expects($this->once())->method('create')->willReturn($entity);
        $data = [
            '_entities' => [
                ['data']
            ]
        ];
        $module = $this->getInstance($data);
        $this->assertEquals([$entity], $module->getSearchableEntities());
        //call twice to test memoizing
        $this->assertEquals([$entity], $module->getSearchableEntities());

        $this->assertEquals([], $this->getInstance([])->getSearchableEntities());
    }

    /**
     * @covers \App\Model\Module::getAclMenuParents
     * @covers \App\Model\Module::__construct
     */
    public function testGetAclMenuParents()
    {
        $menuConfig = [
            'level1' => [
                'label' => 'Level 1',
            ],
            'level2' => [
                'label' => 'Level 2',
                'parent' => 'level1'
            ],
            'level22' => [
                'label' => 'Level 22',
                'parent' => 'level1',
                'acl' => ['acl1', 'acl2']
            ],
            'level3' => [
                'label' => 'Level 3',
                'parent' => 'level2'
            ]
        ];
        $this->assertEquals([],  $this->getInstance(['menu_parent' => ''], $menuConfig)->getAclMenuParents());

        $instance = $this->getInstance(['menu_parent' => 'level1'], $menuConfig);
        $this->assertEquals(['level1'], $instance->getAclMenuParents());
        //call twice for memoizing
        $this->assertEquals(['level1'], $instance->getAclMenuParents());

        $this->assertEquals(['level1', 'level2'], $this->getInstance(['menu_parent' => 'level2'], $menuConfig)->getAclMenuParents());

        $this->assertEquals(['acl1', 'acl2'], $this->getInstance(['menu_parent' => 'level22'], $menuConfig)->getAclMenuParents());

        $this->assertEquals(['level1', 'level2', 'level3'], $this->getInstance(['menu_parent' => 'level3'], $menuConfig)->getAclMenuParents());

        $this->assertEquals(['missing'], $this->getInstance(['menu_parent' => 'missing'], $menuConfig)->getAclMenuParents());
    }

    /**
     * @covers \App\Model\Module::isFrontend
     * @covers \App\Model\Module::initEntityCacheData
     * @covers \App\Model\Module::__construct
     */
    public function testIsFrontend()
    {
        $entity = $this->createMock(Entity::class);
        $entity->method('isFrontendView')->willReturn(true);
        $this->entityFactory->expects($this->once())->method('create')->willReturn($entity);
        $data = [
            '_entities' => [
                ['data']
            ]
        ];
        $module = $this->getInstance($data);
        $this->assertTrue($module->isFrontend());
        //call twice to test memoizing
        $this->assertTrue($module->isFrontend());

        $this->assertFalse($this->getInstance([])->isFrontend());
    }

    /**
     * @covers \App\Model\Module::getFrontendViewEntities
     * @covers \App\Model\Module::initEntityCacheData
     * @covers \App\Model\Module::__construct
     */
    public function testGetFrontendViewEntities()
    {
        $entity = $this->createMock(Entity::class);
        $entity->method('isFrontendView')->willReturn(true);
        $this->entityFactory->expects($this->once())->method('create')->willReturn($entity);
        $data = [
            '_entities' => [
                ['data']
            ]
        ];
        $module = $this->getInstance($data);
        $this->assertEquals([$entity], $module->getFrontendViewEntities());
        //call twice to test memoizing
        $this->assertEquals([$entity], $module->getFrontendViewEntities());

        $this->assertEquals([], $this->getInstance([])->getFrontendViewEntities());
    }

    /**
     * @covers \App\Model\Module::getFrontendListEntities
     * @covers \App\Model\Module::initEntityCacheData
     * @covers \App\Model\Module::__construct
     */
    public function testGetFrontendListEntities()
    {
        $entity = $this->createMock(Entity::class);
        $entity->method('isFrontendList')->willReturn(true);
        $this->entityFactory->expects($this->once())->method('create')->willReturn($entity);
        $data = [
            '_entities' => [
                ['data']
            ]
        ];
        $module = $this->getInstance($data);
        $this->assertEquals([$entity], $module->getFrontendListEntities());
        //call twice to test memoizing
        $this->assertEquals([$entity], $module->getFrontendListEntities());

        $this->assertEquals([], $this->getInstance([])->getFrontendListEntities());
    }

    /**
     * @covers \App\Model\Module::isUpload
     * @covers \App\Model\Module::initEntityCacheData
     * @covers \App\Model\Module::__construct
     */
    public function testIsUpload()
    {
        $entity = $this->createMock(Entity::class);
        $entity->method('isUpload')->willReturn(true);
        $this->entityFactory->expects($this->once())->method('create')->willReturn($entity);
        $data = [
            '_entities' => [
                ['data']
            ]
        ];
        $module = $this->getInstance($data);
        $this->assertTrue($module->isUpload());
        //call twice to test memoizing
        $this->assertTrue($module->isUpload());

        $this->assertFalse($this->getInstance([])->isUpload());
    }

    /**
     * @covers \App\Model\Module::isStore
     * @covers \App\Model\Module::initEntityCacheData
     * @covers \App\Model\Module::__construct
     */
    public function testIsStore()
    {
        $entity = $this->createMock(Entity::class);
        $entity->method('isStore')->willReturn(true);
        $this->entityFactory->expects($this->once())->method('create')->willReturn($entity);
        $data = [
            '_entities' => [
                ['data']
            ]
        ];
        $module = $this->getInstance($data);
        $this->assertTrue($module->isStore());
        //call twice to test memoizing
        $this->assertTrue($module->isStore());

        $this->assertFalse($this->getInstance([])->isStore());
    }

    /**
     * @covers \App\Model\Module::getStoreEntities
     * @covers \App\Model\Module::initEntityCacheData
     * @covers \App\Model\Module::__construct
     */
    public function testGetStoreEntities()
    {
        $entity = $this->createMock(Entity::class);
        $entity->method('isStore')->willReturn(true);
        $this->entityFactory->expects($this->once())->method('create')->willReturn($entity);
        $data = [
            '_entities' => [
                ['data']
            ]
        ];
        $module = $this->getInstance($data);
        $this->assertEquals([$entity], $module->getStoreEntities());
        //call twice to test memoizing
        $this->assertEquals([$entity], $module->getStoreEntities());

        $this->assertEquals([], $this->getInstance([])->getStoreEntities());
    }

    /**
     * @covers \App\Model\Module::isProcessor
     * @covers \App\Model\Module::initEntityCacheData
     * @covers \App\Model\Module::__construct
     */
    public function testIsProcessor()
    {
        $entity = $this->createMock(Entity::class);
        $attribute = $this->createMock(Attribute::class);
        $entity->method('getAttributesWithProcessor')->willReturn(['save' => [$attribute]]);
        $this->entityFactory->expects($this->once())->method('create')->willReturn($entity);
        $data = [
            '_entities' => [
                ['data']
            ]
        ];
        $module = $this->getInstance($data);
        $this->assertTrue($module->isProcessor('save'));
        //call twice to test memoizing
        $this->assertTrue($module->isProcessor('save'));

        $this->assertTrue($module->isProcessor('provider'));
        //call twice to test memoizing
        $this->assertTrue($module->isProcessor('provider'));
    }

    /**
     * @covers \App\Model\Module::isProcessorWithType
     * @covers \App\Model\Module::initEntityCacheData
     * @covers \App\Model\Module::__construct
     */
    public function testIsProcessorWithType()
    {
        $entity = $this->createMock(Entity::class);
        $attribute = $this->createMock(Attribute::class);
        $entity->method('getAttributesWithProcessor')->willReturn(['date' => $attribute]);
        $this->entityFactory->expects($this->once())->method('create')->willReturn($entity);
        $data = [
            '_entities' => [
                ['data']
            ]
        ];
        $module = $this->getInstance($data);
        $this->assertTrue($module->isProcessorWithType('save', 'date'));
        //call twice to test memoizing
        $this->assertTrue($module->isProcessorWithType('save', 'date'));
    }

    /**
     * @covers \App\Model\Module::isProductAttribute
     * @covers \App\Model\Module::initEntityCacheData
     * @covers \App\Model\Module::__construct
     */
    public function testIsProductAttribute()
    {
        $entity = $this->createMock(Entity::class);
        $entity->method('isProductAttribute')->willReturn(true);
        $this->entityFactory->expects($this->once())->method('create')->willReturn($entity);
        $data = [
            '_entities' => [
                ['data']
            ]
        ];
        $module = $this->getInstance($data);
        $this->assertTrue($module->isProductAttribute());
        //call twice to test memoizing
        $this->assertTrue($module->isProductAttribute());

        $this->assertFalse($this->getInstance([])->isProductAttribute());
    }

    /**
     * @covers \App\Model\Module::isProductAttributeSet
     * @covers \App\Model\Module::initEntityCacheData
     * @covers \App\Model\Module::__construct
     */
    public function testIsProductAttributeSet()
    {
        $entity = $this->createMock(Entity::class);
        $entity->method('isProductAttributeSet')->willReturn(true);
        $this->entityFactory->expects($this->once())->method('create')->willReturn($entity);
        $data = [
            '_entities' => [
                ['data']
            ]
        ];
        $module = $this->getInstance($data);
        $this->assertTrue($module->isProductAttributeSet());
        //call twice to test memoizing
        $this->assertTrue($module->isProductAttributeSet());

        $this->assertFalse($this->getInstance([])->isProductAttributeSet());
    }

    /**
     * @covers \App\Model\Module::isOptionAttribute
     * @covers \App\Model\Module::initEntityCacheData
     * @covers \App\Model\Module::__construct
     */
    public function testIsOptionAttribute()
    {
        $entity = $this->createMock(Entity::class);
        $entity->method('hasOptionAttributes')->willReturn(true);
        $this->entityFactory->expects($this->once())->method('create')->willReturn($entity);
        $data = [
            '_entities' => [
                ['data']
            ]
        ];
        $module = $this->getInstance($data);
        $this->assertTrue($module->isOptionAttribute());
        //call twice to test memoizing
        $this->assertTrue($module->isOptionAttribute());

        $this->assertFalse($this->getInstance([])->isOptionAttribute());
    }

    /**
     * @covers \App\Model\Module::hasTopMenu
     * @covers \App\Model\Module::initEntityCacheData
     * @covers \App\Model\Module::__construct
     */
    public function testHasTopMenu()
    {
        $entity = $this->createMock(Entity::class);
        $entity->method('getMenuLink')->willReturn(1);
        $this->entityFactory->expects($this->once())->method('create')->willReturn($entity);
        $data = [
            '_entities' => [
                ['data']
            ]
        ];
        $module = $this->getInstance($data);
        $this->assertTrue($module->hasTopMenu());
        //call twice to test memoizing
        $this->assertTrue($module->hasTopMenu());

        $this->assertFalse($this->getInstance([])->hasTopMenu());
    }

    /**
     * @covers \App\Model\Module::hasFooterMenu
     * @covers \App\Model\Module::initEntityCacheData
     * @covers \App\Model\Module::__construct
     */
    public function testHasFooterMenu()
    {
        $entity = $this->createMock(Entity::class);
        $entity->method('getMenuLink')->willReturn(2);
        $this->entityFactory->expects($this->once())->method('create')->willReturn($entity);
        $data = [
            '_entities' => [
                ['data']
            ]
        ];
        $module = $this->getInstance($data);
        $this->assertTrue($module->hasFooterMenu());
        //call twice to test memoizing
        $this->assertTrue($module->hasFooterMenu());

        $this->assertFalse($this->getInstance([])->hasFooterMenu());
    }

    /**
     * @covers \App\Model\Module::getFileEntities
     * @covers \App\Model\Module::initEntityCacheData
     * @covers \App\Model\Module::__construct
     */
    public function testGetFileEntities()
    {
        $entity = $this->createMock(Entity::class);
        $entity->method('hasAttributeType')->willReturn(true);
        $this->entityFactory->expects($this->once())->method('create')->willReturn($entity);
        $data = [
            '_entities' => [
                ['data']
            ]
        ];
        $module = $this->getInstance($data);
        $this->assertEquals([$entity], $module->getFileEntities());
        //call twice to test memoizing
        $this->assertEquals([$entity], $module->getFileEntities());

        $this->assertEquals([], $this->getInstance([])->getFileEntities());
    }

    /**
     * @covers \App\Model\Module::isImage
     * @covers \App\Model\Module::initEntityCacheData
     * @covers \App\Model\Module::__construct
     */
    public function testIsImage()
    {
        $entity = $this->createMock(Entity::class);
        $entity->method('hasAttributeType')->willReturn(true);
        $this->entityFactory->expects($this->once())->method('create')->willReturn($entity);
        $data = [
            '_entities' => [
                ['data']
            ]
        ];
        $module = $this->getInstance($data);
        $this->assertTrue($module->isImage());
        //call twice to test memoizing
        $this->assertTrue($module->isImage());

        $this->assertFalse($this->getInstance([])->isImage());
    }

    /**
     * @covers \App\Model\Module::getImageEntities
     * @covers \App\Model\Module::initEntityCacheData
     * @covers \App\Model\Module::__construct
     */
    public function testGetImageEntities()
    {
        $entity = $this->createMock(Entity::class);
        $entity->method('hasAttributeType')->willReturn(true);
        $this->entityFactory->expects($this->once())->method('create')->willReturn($entity);
        $data = [
            '_entities' => [
                ['data']
            ]
        ];
        $module = $this->getInstance($data);
        $this->assertEquals([$entity], $module->getImageEntities());
        //call twice to test memoizing
        $this->assertEquals([$entity], $module->getImageEntities());

        $this->assertEquals([], $this->getInstance([])->getImageEntities());
    }

    /**
     * @covers \App\Model\Module::getMainMenuEntities
     * @covers \App\Model\Module::initEntityCacheData
     * @covers \App\Model\Module::__construct
     */
    public function testGetMainMenuEntities()
    {
        $entity = $this->createMock(Entity::class);
        $entity->method('getMenuLink')->willReturn(1);
        $this->entityFactory->expects($this->once())->method('create')->willReturn($entity);
        $data = [
            '_entities' => [
                ['data']
            ]
        ];
        $module = $this->getInstance($data);
        $this->assertEquals([$entity], $module->getMainMenuEntities());
        //call twice to test memoizing
        $this->assertEquals([$entity], $module->getMainMenuEntities());

        $this->assertEquals([], $this->getInstance([])->getMainMenuEntities());
    }

    /**
     * @covers \App\Model\Module::getFooterLinksEntities
     * @covers \App\Model\Module::initEntityCacheData
     * @covers \App\Model\Module::__construct
     */
    public function testGetFooterLinksEntities()
    {
        $entity = $this->createMock(Entity::class);
        $entity->method('getMenuLink')->willReturn(2);
        $this->entityFactory->expects($this->once())->method('create')->willReturn($entity);
        $data = [
            '_entities' => [
                ['data']
            ]
        ];
        $module = $this->getInstance($data);
        $this->assertEquals([$entity], $module->getFooterLinksEntities());
        //call twice to test memoizing
        $this->assertEquals([$entity], $module->getFooterLinksEntities());

        $this->assertEquals([], $this->getInstance([])->getFooterLinksEntities());
    }

    /**
     * @covers \App\Model\Module::getMockObjectUse
     * @covers \App\Model\Module::__construct
     */
    public function testGetMockObjectUse()
    {
        $this->assertEquals(
            "\n" . 'use PHPUnit\Framework\MockObject\MockObject;',
            $this->getInstance([])->getMockObjectUse()
        );
    }

    /**
     * @covers \App\Model\Module::getMockObjectClass
     * @covers \App\Model\Module::__construct
     */
    public function testGetMockObjectClass()
    {
        $this->assertEquals('MockObject', $this->getInstance([])->getMockObjectClass());
    }

    /**
     * @covers \App\Model\Module::getUmcCrudNamespace
     * @covers \App\Model\Module::__construct
     */
    public function testGetUmcCrudNamespace()
    {
        $this->assertEquals(
            'Umc',
            $this->getInstance(['umc_crud' => true, 'namespace' => 'Ns'])->getUmcCrudNamespace()
        );

        $this->assertEquals(
            'Ns',
            $this->getInstance(['umc_crud' => false, 'namespace' => 'Ns'])->getUmcCrudNamespace()
        );
    }

    /**
     * @covers \App\Model\Module::getUmcModuleName
     * @covers \App\Model\Module::__construct
     */
    public function testGetUmcModuleName()
    {
        $this->assertEquals(
            'Crud',
            $this->getInstance(['umc_crud' => true, 'module_name' => 'Md'])->getUmcModuleName()
        );

        $this->assertEquals(
            'Md',
            $this->getInstance(['umc_crud' => false, 'module_name' => 'Md'])->getUmcModuleName()
        );
    }

    /**
     * @covers \App\Model\Module::getNullSaveDataProcessor
     * @covers \App\Model\Module::__construct
     */
    public function testGetNullSaveDataProcessor()
    {
        $expected = ['Umc', 'Crud', 'Ui', 'SaveDataProcessor', 'NullProcessor'];
        $this->stringUtil->expects($this->once())->method('glueClassParts')->with($expected)->willReturn('class');
        $this->assertEquals('class', $this->getInstance(['umc_crud' => 1])->getNullSaveDataProcessor());
    }

    /**
     * @covers \App\Model\Module::getNullSaveDataProcessor
     * @covers \App\Model\Module::__construct
     */
    public function testGetNullSaveDataProcessorNoUmcCrud()
    {
        $expected = ['Ns', 'Md', 'Ui', 'SaveDataProcessor', 'NullProcessor'];
        $this->stringUtil->expects($this->once())->method('glueClassParts')->with($expected)->willReturn('class');
        $settings = [
            'umc_crud' => false,
            'namespace' => 'Ns',
            'module_name' => 'Md'
        ];
        $this->assertEquals('class', $this->getInstance($settings)->getNullSaveDataProcessor());
    }

    /**
     * @covers \App\Model\Module::getNullFormDataModifier
     * @covers \App\Model\Module::__construct
     */
    public function testGetNullFormDataModifier()
    {
        $expected = ['Umc', 'Crud', 'Ui', 'Form', 'DataModifier', 'NullModifier'];
        $this->stringUtil->expects($this->once())->method('glueClassParts')->with($expected)->willReturn('class');
        $this->assertEquals('class', $this->getInstance(['umc_crud' => 1])->getNullFormDataModifier());
    }

    /**
     * @covers \App\Model\Module::getNullFormDataModifier
     * @covers \App\Model\Module::__construct
     */
    public function testGetNullFormDataModifierNoUmcCrud()
    {
        $expected = ['Ns', 'Md', 'Ui', 'Form', 'DataModifier', 'NullModifier'];
        $this->stringUtil->expects($this->once())->method('glueClassParts')->with($expected)->willReturn('class');
        $settings = [
            'umc_crud' => false,
            'namespace' => 'Ns',
            'module_name' => 'Md'
        ];
        $this->assertEquals('class', $this->getInstance($settings)->getNullFormDataModifier());
    }

    /**
     * @covers \App\Model\Module::getAdminRoutePrefix
     * @covers \App\Model\Module::__construct
     */
    public function testGetAdminRoutePrefix()
    {
        $this->assertEquals('modulename', $this->getInstance(['module_name' => 'module_name'])->getAdminRoutePrefix());
    }

    /**
     * @covers \App\Model\Module::getProcessorTypes
     * @covers \App\Model\Module::__construct
     */
    public function testGetProcessorTypes()
    {
        $this->assertEquals(2, count($this->getInstance([])->getProcessorTypes()));
    }

    /**
     * @param $data
     * @param array $menuConfig
     * @return Module
     */
    private function getInstance($data, $menuConfig = []): Module
    {
        return  new Module(
            $this->stringUtil,
            $this->entityFactory,
            [],
            $menuConfig,
            $data
        );
    }
}
