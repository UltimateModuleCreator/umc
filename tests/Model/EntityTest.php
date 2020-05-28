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
use App\Model\Module;
use App\Util\StringUtil;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class EntityTest extends TestCase
{
    /**
     * @var StringUtil | MockObject
     */
    private $stringUtil;
    /**
     * @var Attribute\Factory | MockObject
     */
    private $attributeFactory;
    /**
     * @var Module | MockObject
     */
    private $module;

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->stringUtil = $this->createMock(StringUtil::class);
        $this->attributeFactory = $this->createMock(Attribute\Factory::class);
        $this->module = $this->createMock(Module::class);
    }

    /**
     * @covers \App\Model\Entity::toArray()
     * @covers \App\Model\Entity::__construct()
     */
    public function testToArray()
    {
        $attribute = $this->createMock(Attribute::class);
        $attribute->expects($this->once())->method('toArray');
        $this->attributeFactory->expects($this->once())->method('create')->willReturn($attribute);
        $result = $this->getInstance(['_attributes' => [[]]])->toArray();
        $this->assertArrayHasKey('name_singular', $result);
        $this->assertArrayHasKey('label_plural', $result);
        $this->assertArrayHasKey('_attributes', $result);
    }

    /**
     * @covers \App\Model\Entity::getNameAttribute
     * @covers \App\Model\Entity::__construct()
     */
    public function testGetNameAttribute()
    {
        /** @var Attribute | MockObject $attribute1 */
        $attribute1 = $this->createMock(Attribute::class);
        /** @var Attribute | MockObject $attribute2 */
        $attribute2 = $this->createMock(Attribute::class);
        $attribute2->method('isName')->willReturn(true);
        $this->attributeFactory->expects($this->exactly(2))->method('create')
            ->willReturnOnConsecutiveCalls($attribute1, $attribute2);
        $entity = $this->getInstance(['_attributes' => [['data'], ['data']]]);
        $this->assertEquals($attribute2, $entity->getNameAttribute());
    }

    /**
     * @covers \App\Model\Entity::getNameAttribute
     * @covers \App\Model\Entity::__construct
     */
    public function testGetNameAttributeNoAttribute()
    {
        /** @var Attribute | MockObject $attribute1 */
        $attribute1 = $this->createMock(Attribute::class);
        /** @var Attribute | MockObject $attribute2 */
        $attribute2 = $this->createMock(Attribute::class);
        $this->attributeFactory->expects($this->exactly(2))->method('create')
            ->willReturnOnConsecutiveCalls($attribute1, $attribute2);
        $entity = $this->getInstance(['_attributes' => [['data'], ['data']]]);
        $this->assertNull($entity->getNameAttribute());
    }

    /**
     * @covers \App\Model\Entity::getAttributes
     * @covers \App\Model\Entity::__construct
     */
    public function testGetAttributes()
    {
        /** @var Attribute | MockObject $attribute1 */
        $attribute1 = $this->createMock(Attribute::class);
        /** @var Attribute | MockObject $attribute2 */
        $attribute2 = $this->createMock(Attribute::class);
        $this->attributeFactory->expects($this->exactly(2))->method('create')
            ->willReturnOnConsecutiveCalls($attribute1, $attribute2);
        $entity = $this->getInstance(['_attributes' => [['data'], ['data']]]);
        $this->assertEquals([$attribute1, $attribute2], $entity->getAttributes());
    }

    /**
     * @covers \App\Model\Entity::getModule
     * @covers \App\Model\Entity::__construct
     */
    public function testGetModule()
    {
        $this->assertEquals($this->module, $this->getInstance([])->getModule());
    }

    /**
     * @covers \App\Model\Entity::getNameSingular
     * @covers \App\Model\Entity::__construct
     */
    public function testGetNameSingular()
    {
        $this->assertEquals('name', $this->getInstance(['name_singular' => 'name'])->getNameSingular());
    }

    /**
     * @covers \App\Model\Entity::getNamePlural
     * @covers \App\Model\Entity::__construct
     */
    public function testGetNamePlural()
    {
        $this->assertEquals('name', $this->getInstance(['name_plural' => 'name'])->getNamePlural());
    }

    /**
     * @covers \App\Model\Entity::getLabelSingular
     * @covers \App\Model\Entity::__construct
     */
    public function testGetLabelSingular()
    {
        $this->assertEquals('name', $this->getInstance(['label_singular' => 'name'])->getLabelSingular());
    }

    /**
     * @covers \App\Model\Entity::getLabelPlural
     * @covers \App\Model\Entity::__construct
     */
    public function testGetLabelPlural()
    {
        $this->assertEquals('name', $this->getInstance(['label_plural' => 'name'])->getLabelPlural());
    }

    /**
     * @covers \App\Model\Entity::isSearch
     * @covers \App\Model\Entity::__construct
     */
    public function testIsSearch()
    {
        $this->assertTrue($this->getInstance(['search' => 1])->isSearch());
        $this->assertFalse($this->getInstance([])->isSearch());
        $this->assertFalse($this->getInstance(['search' => false])->isSearch());
    }

    /**
     * @covers \App\Model\Entity::isStore
     * @covers \App\Model\Entity::__construct
     */
    public function testIsStore()
    {
        $this->assertTrue($this->getInstance(['store' => 1])->isStore());
        $this->assertFalse($this->getInstance([])->isStore());
        $this->assertFalse($this->getInstance(['store' => false])->isStore());
    }

    /**
     * @covers \App\Model\Entity::isFrontendList
     * @covers \App\Model\Entity::__construct
     */
    public function testIsFrontendList()
    {
        $this->assertTrue($this->getInstance(['frontend_list' => 1])->isFrontendList());
        $this->assertFalse($this->getInstance([])->isFrontendList());
        $this->assertFalse($this->getInstance(['frontend_list' => false])->isFrontendList());
    }

    /**
     * @covers \App\Model\Entity::isFrontendView
     * @covers \App\Model\Entity::__construct
     */
    public function testIsFrontendView()
    {
        $this->assertTrue($this->getInstance(['frontend_view' => 1])->isFrontendView());
        $this->assertFalse($this->getInstance([])->isFrontendView());
        $this->assertFalse($this->getInstance(['frontend_view' => false])->isFrontendView());
    }

    /**
     * @covers \App\Model\Entity::isSeo
     * @covers \App\Model\Entity::__construct
     */
    public function testIsSeo()
    {
        $this->assertTrue($this->getInstance(['seo' => 1, 'frontend_view' => 1])->isSeo());
        $this->assertFalse($this->getInstance(['seo' => 1, 'frontend_view' => 0])->isSeo());
        $this->assertFalse($this->getInstance([])->isSeo());
        $this->assertFalse($this->getInstance(['seo' => false, 'frontend_view'])->isSeo());
    }

    /**
     * @covers \App\Model\Entity::getMenuLink
     * @covers \App\Model\Entity::__construct
     */
    public function testGetMenuLink()
    {
        $this->assertEquals(1, $this->getInstance(['menu_link' => 1, 'frontend_list' => 1])->getMenuLink());
        $this->assertEquals(0, $this->getInstance(['menu_link' => 2, 'frontend_list' => 0])->getMenuLink());
    }

    /**
     * @covers \App\Model\Entity::isFrontend
     * @covers \App\Model\Entity::__construct
     */
    public function testHasFrontend()
    {
        $entity = $this->getInstance([]);
        $this->assertFalse($entity->isFrontend());
        $entity = $this->getInstance(['frontend_list' => 1]);
        $this->assertTrue($entity->isFrontend());
        $entity = $this->getInstance(['frontend_view' => 1]);
        $this->assertTrue($entity->isFrontend());
        $entity = $this->getInstance(['frontend_list' => 1, 'frontend_view' => 1]);
        $this->assertTrue($entity->isFrontend());
        $entity = $this->getInstance(['frontend_list' => 0, 'frontend_view' => 0]);
        $this->assertFalse($entity->isFrontend());
    }

    /**
     * @covers \App\Model\Entity::isProductAttribute
     * @covers \App\Model\Entity::initAttributeCacheData
     * @covers \App\Model\Entity::__construct
     */
    public function testIsProductAttribute()
    {
        $attribute1 = $this->createMock(Attribute::class);
        $attribute1->expects($this->once())->method('isProductAttribute')->willReturn(true);
        $attribute2 = $this->createMock(Attribute::class);
        $attribute2->expects($this->once())->method('isProductAttribute')->willReturn(false);
        $this->attributeFactory->expects($this->exactly(2))->method('create')
            ->willReturnOnConsecutiveCalls($attribute1, $attribute2);
        $this->assertTrue($this->getInstance(['_attributes' => [[], []]])->isProductAttribute());
        $this->assertFalse($this->getInstance([])->isProductAttribute());
    }

    /**
     * @covers \App\Model\Entity::isProductAttributeSet
     * @covers \App\Model\Entity::initAttributeCacheData
     * @covers \App\Model\Entity::__construct
     */
    public function testIsProductAttributeSet()
    {
        $attribute1 = $this->createMock(Attribute::class);
        $attribute1->expects($this->once())->method('isProductAttributeSet')->willReturn(true);
        $attribute2 = $this->createMock(Attribute::class);
        $attribute2->expects($this->once())->method('isProductAttributeSet')->willReturn(false);
        $this->attributeFactory->expects($this->exactly(2))->method('create')
            ->willReturnOnConsecutiveCalls($attribute1, $attribute2);
        $this->assertTrue($this->getInstance(['_attributes' => [[], []]])->isProductAttributeSet());
        $this->assertFalse($this->getInstance([])->isProductAttributeSet());
    }

    /**
     * @covers \App\Model\Entity::getAttributesWithProcessor
     * @covers \App\Model\Entity::initAttributeCacheData
     * @covers \App\Model\Entity::__construct
     */
    public function testGetAttributesWithProcessor()
    {
        $this->module->method('getProcessorTypes')->willReturn(['save', 'provider']);
        $attribute1 = $this->createMock(Attribute::class);
        $attribute1->method('getProcessorTypes')->willReturnMap([
            ['save', ['processor']],
            ['provider', []]
        ]);
        $attribute2 = $this->createMock(Attribute::class);
        $attribute2->method('getProcessorTypes')->willReturn([]);
        $this->attributeFactory->expects($this->exactly(2))->method('create')
            ->willReturnOnConsecutiveCalls($attribute1, $attribute2);
        $instance = $this->getInstance(['_attributes' => [[], []]]);
        $this->assertEquals(
            ['processor' => [$attribute1]],
            $instance->getAttributesWithProcessor('save')
        );
        $this->assertEquals(
            [],
            $instance->getAttributesWithProcessor('provider')
        );
    }

    /**
     * @covers \App\Model\Entity::getAttributesWithProcessorType
     * @covers \App\Model\Entity::initAttributeCacheData
     * @covers \App\Model\Entity::__construct
     */
    public function testGetAttributesWithProcessorType()
    {
        $this->module->method('getProcessorTypes')->willReturn(['save', 'provider']);
        $attribute1 = $this->createMock(Attribute::class);
        $attribute1->method('getProcessorTypes')->willReturnMap([
            ['save', ['processor']],
            ['provider', []]
        ]);
        $attribute2 = $this->createMock(Attribute::class);
        $attribute2->method('getProcessorTypes')->willReturn([]);
        $this->attributeFactory->expects($this->exactly(2))->method('create')
            ->willReturnOnConsecutiveCalls($attribute1, $attribute2);
        $instance = $this->getInstance(['_attributes' => [[], []]]);
        $this->assertEquals(
            [$attribute1],
            $instance->getAttributesWithProcessorType('save', 'processor')
        );
    }

    /**
     * @covers \App\Model\Entity::getFullTextAttributes
     * @covers \App\Model\Entity::initAttributeCacheData
     * @covers \App\Model\Entity::__construct
     */
    public function testGetFullTextAttributes()
    {
        $attribute1 = $this->createMock(Attribute::class);
        $attribute1->expects($this->once())->method('isFullText')->willReturn(true);
        $attribute2 = $this->createMock(Attribute::class);
        $attribute2->expects($this->once())->method('isFullText')->willReturn(false);
        $this->attributeFactory->expects($this->exactly(2))->method('create')
            ->willReturnOnConsecutiveCalls($attribute1, $attribute2);
        $this->assertEquals([$attribute1], $this->getInstance(['_attributes' => [[], []]])->getFullTextAttributes());
        $this->assertEquals([], $this->getInstance([])->getFullTextAttributes());
    }

    /**
     * @covers \App\Model\Entity::getOptionAttributes
     * @covers \App\Model\Entity::initAttributeCacheData
     * @covers \App\Model\Entity::__construct
     */
    public function testGetOptionAttributes()
    {
        $attribute1 = $this->createMock(Attribute::class);
        $attribute1->expects($this->once())->method('isManualOptions')->willReturn(true);
        $attribute2 = $this->createMock(Attribute::class);
        $attribute2->expects($this->once())->method('isManualOptions')->willReturn(false);
        $this->attributeFactory->expects($this->exactly(2))->method('create')
            ->willReturnOnConsecutiveCalls($attribute1, $attribute2);
        $this->assertEquals([$attribute1], $this->getInstance(['_attributes' => [[], []]])->getOptionAttributes());
        $this->assertEquals([], $this->getInstance([])->getOptionAttributes());
    }

    /**
     * @covers \App\Model\Entity::hasOptionAttributes
     * @covers \App\Model\Entity::initAttributeCacheData
     * @covers \App\Model\Entity::__construct
     */
    public function testHasOptionAttributes()
    {
        $attribute1 = $this->createMock(Attribute::class);
        $attribute1->expects($this->once())->method('isManualOptions')->willReturn(true);
        $attribute2 = $this->createMock(Attribute::class);
        $attribute2->expects($this->once())->method('isManualOptions')->willReturn(false);
        $this->attributeFactory->expects($this->exactly(2))->method('create')
            ->willReturnOnConsecutiveCalls($attribute1, $attribute2);
        $this->assertTrue($this->getInstance(['_attributes' => [[], []]])->hasOptionAttributes());
        $this->assertFalse($this->getInstance([])->hasOptionAttributes());
    }

    /**
     * @covers \App\Model\Entity::getAttributesWithType
     * @covers \App\Model\Entity::initAttributeCacheData
     * @covers \App\Model\Entity::__construct
     */
    public function testGetAttributesWithType()
    {
        $attribute1 = $this->createMock(Attribute::class);
        $attribute1->expects($this->once())->method('getType')->willReturn('text');
        $attribute2 = $this->createMock(Attribute::class);
        $attribute2->expects($this->once())->method('getType')->willReturn('textarea');
        $this->attributeFactory->expects($this->exactly(2))->method('create')
            ->willReturnOnConsecutiveCalls($attribute1, $attribute2);
        $instance = $this->getInstance(['_attributes' => [[], []]]);
        $this->assertEquals([$attribute1], $instance->getAttributesWithType('text'));
        $this->assertEquals([$attribute2], $instance->getAttributesWithType('textarea'));
        $this->assertEquals([], $instance->getAttributesWithType('missing'));
    }

    /**
     * @covers \App\Model\Entity::getMainTableName
     * @covers \App\Model\Entity::__construct
     */
    public function testGetMainTableName()
    {
        $this->module->method('getModuleName')->willReturn('Md');
        $this->stringUtil->expects($this->once())->method('snake')->willReturnArgument(0);
        $expected = 'Md_name';
        $this->assertEquals($expected, $this->getInstance(['name_singular' => 'name'])->getMainTableName());
    }

    /**
     * @covers \App\Model\Entity::getStoreTableName
     * @covers \App\Model\Entity::__construct
     */
    public function testGetStoreTableName()
    {
        $this->module->method('getModuleName')->willReturn('Md');
        $this->stringUtil->expects($this->once())->method('snake')->willReturnArgument(0);
        $expected = 'Md_name_store';
        $this->assertEquals($expected, $this->getInstance(['name_singular' => 'name'])->getStoreTableName());
    }

    /**
     * @covers \App\Model\Entity::isUpload
     * @covers \App\Model\Entity::initAttributeCacheData
     * @covers \App\Model\Entity::__construct
     */
    public function testIsUpload()
    {
        $attribute1 = $this->createMock(Attribute::class);
        $attribute1->expects($this->once())->method('isUpload')->willReturn(true);
        $attribute2 = $this->createMock(Attribute::class);
        $attribute2->expects($this->once())->method('isUpload')->willReturn(false);
        $this->attributeFactory->expects($this->exactly(2))->method('create')
            ->willReturnOnConsecutiveCalls($attribute1, $attribute2);
        $this->assertTrue($this->getInstance(['_attributes' => [[], []]])->isUpload());
        $this->assertFalse($this->getInstance([])->isUpload());
    }

    /**
     * @covers \App\Model\Entity::getComposerDependencies
     * @covers \App\Model\Entity::__construct
     */
    public function testGetComposerDependencies()
    {
        $instance = $this->getInstance(['frontend_view' => true]);
        $this->assertTrue(in_array('magento/module-theme', $instance->getComposerDependencies()));
        //call twice to test memoizing
        $this->assertTrue(in_array('magento/module-theme', $instance->getComposerDependencies()));

        $this->assertTrue(
            in_array(
                'magento/module-store',
                $this->getInstance(['store' => true])->getComposerDependencies()
            )
        );
        $attribute = $this->createMock(Attribute::class);
        $attribute->method('getType')->willReturn('wysiwyg');
        $attribute->method('isProductAttribute')->willReturn(true);
        $attribute->method('isUpload')->willReturn(true);
        $this->attributeFactory->expects($this->once())->method('create')->willReturn($attribute);
        $result = $this->getInstance(['frontend_view' => true, '_attributes' => [[]]])->getComposerDependencies();
        $this->assertTrue(in_array('magento/module-widget', $result));
        $this->assertTrue(in_array('magento/module-media-storage', $result));
        $this->assertTrue(in_array('magento/module-catalog', $result));
        $this->assertTrue(in_array('magento/module-eav', $result));
    }

    /**
     * @covers \App\Model\Entity::getModuleDependencies
     * @covers \App\Model\Entity::__construct
     */
    public function testGetModuleDependencies()
    {
        $instance = $this->getInstance(['frontend_view' => true]);
        $this->assertTrue(in_array('Magento_Theme', $instance->getModuleDependencies()));
        //call twice to test memoizing
        $this->assertTrue(in_array('Magento_Theme', $instance->getModuleDependencies()));

        $this->assertTrue(
            in_array(
                'Magento_Store',
                $this->getInstance(['store' => true])->getModuleDependencies()
            )
        );
        $attribute = $this->createMock(Attribute::class);
        $attribute->method('getType')->willReturn('wysiwyg');
        $attribute->method('isProductAttribute')->willReturn(true);
        $attribute->method('isUpload')->willReturn(true);
        $this->attributeFactory->expects($this->once())->method('create')->willReturn($attribute);
        $result = $this->getInstance(['frontend_view' => true, '_attributes' => [[]]])->getModuleDependencies();
        $this->assertTrue(in_array('Magento_Widget', $result));
        $this->assertTrue(in_array('Magento_MediaStorage', $result));
        $this->assertTrue(in_array('Magento_Catalog', $result));
        $this->assertTrue(in_array('Magento_Eav', $result));
    }

    /**
     * @covers \App\Model\Entity::getAclName
     * @covers \App\Model\Entity::__construct
     */
    public function testGetAclName()
    {
        $this->module->method('getAclName')->willReturn('acl_name');
        $this->stringUtil->expects($this->once())->method('snake')->willReturnArgument(0);
        $expected = 'acl_name_name';
        $this->assertEquals($expected, $this->getInstance(['name_singular' => 'name'])->getAclName());
    }

    /**
     * @covers \App\Model\Entity::getModel
     * @covers \App\Model\Entity::__construct
     */
    public function testGetModelNoSuffix()
    {
        $this->module->method('getNamespace')->willReturn('Ns');
        $this->module->method('getModuleName')->willReturn('Md');
        $params = ['Ns', 'Md', 'Model', 'name'];
        $this->stringUtil->expects($this->once())->method('glueClassParts')->with($params)->willReturn('model');
        $this->assertEquals('model', $this->getInstance(['name_singular' => 'name'])->getModel());
    }

    /**
     * @covers \App\Model\Entity::getModel
     * @covers \App\Model\Entity::__construct
     */
    public function testGetModel()
    {
        $this->module->method('getNamespace')->willReturn('Ns');
        $this->module->method('getModuleName')->willReturn('Md');
        $params = ['Ns', 'Md', 'Model', 'name'];
        $this->stringUtil->expects($this->once())->method('glueClassParts')->with($params)->willReturn('model');
        $this->assertEquals('model_suffix', $this->getInstance(['name_singular' => 'name'])->getModel('_suffix'));
    }

    /**
     * @covers \App\Model\Entity::getAdminController
     * @covers \App\Model\Entity::__construct
     */
    public function testGetAdminController()
    {
        $this->module->method('getNamespace')->willReturn('Ns');
        $this->module->method('getModuleName')->willReturn('Md');
        $params = ['Ns', 'Md', 'Controller', 'Adminhtml', 'name', 'controller_name'];
        $this->stringUtil->expects($this->once())->method('glueClassParts')->with($params)->willReturn('controller');
        $this->assertEquals(
            'controller',
            $this->getInstance(['name_singular' => 'name'])->getAdminController('controller_name')
        );
    }

    /**
     * @covers \App\Model\Entity::getResourceModel
     * @covers \App\Model\Entity::__construct
     */
    public function testGetResourceModel()
    {
        $this->module->method('getNamespace')->willReturn('Ns');
        $this->module->method('getModuleName')->willReturn('Md');
        $params = ['Ns', 'Md', 'Model', 'ResourceModel', 'name'];
        $this->stringUtil->expects($this->once())->method('glueClassParts')->with($params)
            ->willReturn('resource_model');
        $this->assertEquals(
            'resource_model',
            $this->getInstance(['name_singular' => 'name'])->getResourceModel()
        );
    }

    /**
     * @covers \App\Model\Entity::getCollectionModel
     * @covers \App\Model\Entity::__construct
     */
    public function testGetCollectionModel()
    {
        $this->module->method('getNamespace')->willReturn('Ns');
        $this->module->method('getModuleName')->willReturn('Md');
        $params = ['Ns', 'Md', 'Model', 'ResourceModel', 'name'];
        $this->stringUtil->expects($this->once())->method('glueClassParts')->with($params)
            ->willReturn('resource_model');
        $this->assertEquals(
            'resource_model\Collection',
            $this->getInstance(['name_singular' => 'name'])->getCollectionModel()
        );
    }

    /**
     * @covers \App\Model\Entity::getRepoModel
     * @covers \App\Model\Entity::__construct
     */
    public function testGetRepoModel()
    {
        $this->module->method('getNamespace')->willReturn('Ns');
        $this->module->method('getModuleName')->willReturn('Md');
        $params = ['Ns', 'Md', 'Model', 'name'];
        $this->stringUtil->expects($this->once())->method('glueClassParts')->with($params)
            ->willReturn('model');
        $this->assertEquals(
            'modelRepo',
            $this->getInstance(['name_singular' => 'name'])->getRepoModel()
        );
    }

    /**
     * @covers \App\Model\Entity::getListRepoModel
     * @covers \App\Model\Entity::__construct
     */
    public function testGetListRepoModel()
    {
        $this->module->method('getNamespace')->willReturn('Ns');
        $this->module->method('getModuleName')->willReturn('Md');
        $params = ['Ns', 'Md', 'Model', 'name'];
        $this->stringUtil->expects($this->once())->method('glueClassParts')->with($params)
            ->willReturn('model');
        $this->assertEquals(
            'modelListRepo',
            $this->getInstance(['name_singular' => 'name'])->getListRepoModel()
        );
    }

    /**
     * @covers \App\Model\Entity::getSearchModel
     * @covers \App\Model\Entity::__construct
     */
    public function testGetSearchModel()
    {
        $this->module->method('getNamespace')->willReturn('Ns');
        $this->module->method('getModuleName')->willReturn('Md');
        $params = ['Ns', 'Md', 'Model', 'Search', 'name'];
        $this->stringUtil->expects($this->once())->method('glueClassParts')->with($params)
            ->willReturn('model');
        $this->assertEquals(
            'model',
            $this->getInstance(['name_singular' => 'name'])->getSearchModel()
        );
    }

    /**
     * @covers \App\Model\Entity::getInterface
     * @covers \App\Model\Entity::__construct
     */
    public function testGetInterface()
    {
        $this->module->method('getNamespace')->willReturn('Ns');
        $this->module->method('getModuleName')->willReturn('Md');
        $params = ['Ns', 'Md', 'Api', 'Data', 'name'];
        $this->stringUtil->expects($this->once())->method('glueClassParts')->with($params)
            ->willReturn('interface');
        $this->assertEquals(
            'interfaceInterface',
            $this->getInstance(['name_singular' => 'name'])->getInterface()
        );
    }

    /**
     * @covers \App\Model\Entity::getRepoInterface
     * @covers \App\Model\Entity::__construct
     */
    public function testGetRepoInterface()
    {
        $this->module->method('getNamespace')->willReturn('Ns');
        $this->module->method('getModuleName')->willReturn('Md');
        $params = ['Ns', 'Md', 'Api', 'name'];
        $this->stringUtil->expects($this->once())->method('glueClassParts')->with($params)
            ->willReturn('interface');
        $this->assertEquals(
            'interfaceRepositoryInterface',
            $this->getInstance(['name_singular' => 'name'])->getRepoInterface()
        );
    }

    /**
     * @covers \App\Model\Entity::getListRepoInterface
     * @covers \App\Model\Entity::__construct
     */
    public function testGetListRepoInterface()
    {
        $this->module->method('getNamespace')->willReturn('Ns');
        $this->module->method('getModuleName')->willReturn('Md');
        $params = ['Ns', 'Md', 'Api', 'name'];
        $this->stringUtil->expects($this->once())->method('glueClassParts')->with($params)
            ->willReturn('interface');
        $this->assertEquals(
            'interfaceListRepositoryInterface',
            $this->getInstance(['name_singular' => 'name'])->getListRepoInterface()
        );
    }

    /**
     * @covers \App\Model\Entity::getSearchResultsInterface
     * @covers \App\Model\Entity::__construct
     */
    public function testGetSearchResultsInterface()
    {
        $this->module->method('getNamespace')->willReturn('Ns');
        $this->module->method('getModuleName')->willReturn('Md');
        $params = ['Ns', 'Md', 'Api', 'Data', 'name'];
        $this->stringUtil->expects($this->once())->method('glueClassParts')->with($params)
            ->willReturn('interface');
        $this->assertEquals(
            'interfaceSearchResultInterface',
            $this->getInstance(['name_singular' => 'name'])->getSearchResultsInterface()
        );
    }

    /**
     * @covers \App\Model\Entity::getPk
     * @covers \App\Model\Entity::__construct
     */
    public function testGetPk()
    {
        $this->stringUtil->method('snake')->willReturnArgument(0);
        $this->assertEquals('name_id', $this->getInstance(['name_singular' => 'name'])->getPk());
    }

    /**
     * @covers \App\Model\Entity::getUploadFolder
     * @covers \App\Model\Entity::__construct
     */
    public function testGetUploadFolder()
    {
        $this->stringUtil->method('snakeArray')->willReturnArgument(0);
        $instance = $this->getInstance(['name_singular' => 'name']);
        $this->module->method('getModuleName')->willReturn('md');
        $this->assertEquals('md/name/image', $instance->getUploadFolder('image'));
        $this->assertEquals('md/name/tmp/image', $instance->getUploadFolder('image', true));
    }

    /**
     * @covers \App\Model\Entity::getUploadInfoModel
     * @covers \App\Model\Entity::__construct
     */
    public function testGetUploadInfoModel()
    {
        $instance = $this->getInstance(['name_singular' => 'name']);
        $this->module->method('getModuleName')->willReturn('Md');
        $this->stringUtil->method('glueClassParts')->with(['Md', 'name', 'type', 'Info'])->willReturn('model');
        $this->assertEquals('model', $instance->getUploadInfoModel('type'));
    }

    /**
     * @covers \App\Model\Entity::getStoreHandler
     * @covers \App\Model\Entity::__construct
     */
    public function testGetStoreHandler()
    {
        $instance = $this->getInstance(['name_singular' => 'name']);
        $this->module->method('getModuleName')->willReturn('Md');
        $this->stringUtil->method('glueClassParts')->with(['Md', 'name', 'RelateStoreResource', 'type', 'Handler'])
            ->willReturn('model');
        $this->assertEquals('model', $instance->getStoreHandler('type'));
    }

    /**
     * @covers \App\Model\Entity::getVirtualType
     * @covers \App\Model\Entity::__construct
     */
    public function testGetVirtualType()
    {
        $instance = $this->getInstance(['name_singular' => 'name']);
        $this->module->method('getModuleName')->willReturn('Md');
        $this->stringUtil->method('glueClassParts')->with(['Md', 'name', 'suffix'])
            ->willReturn('model');
        $this->assertEquals('model', $instance->getVirtualType('suffix'));
    }

    /**
     * @covers \App\Model\Entity::getEventPrefix
     * @covers \App\Model\Entity::__construct
     */
    public function testGetEventPrefix()
    {
        $instance = $this->getInstance(['name_singular' => 'name']);
        $this->module->method('getNamespace')->willReturn('Ns');
        $this->module->method('getModuleName')->willReturn('Md');
        $this->stringUtil->method('snakeArray')->willReturnArgument(0);
        $expected = 'Ns_Md_name';
        $this->assertEquals($expected, $instance->getEventPrefix());
    }

    /**
     * @covers \App\Model\Entity::getImageAttributes
     * @covers \App\Model\Entity::hasImageAttributes
     * @covers \App\Model\Entity::initAttributeCacheData
     * @covers \App\Model\Entity::__construct
     */
    public function testGetImageAttributes()
    {
        $attribute1 = $this->createMock(Attribute::class);
        $attribute1->expects($this->once())->method('getType')->willReturn('image');
        $this->attributeFactory->expects($this->once())->method('create')
            ->willReturnOnConsecutiveCalls($attribute1);
        $instance = $this->getInstance(['_attributes' => [[]]]);
        $this->assertEquals([$attribute1], $instance->getImageAttributes());
        $this->assertTrue($instance->hasImageAttributes());
    }

    /**
     * @covers \App\Model\Entity::getFileAttributes
     * @covers \App\Model\Entity::hasFileAttributes
     * @covers \App\Model\Entity::initAttributeCacheData
     * @covers \App\Model\Entity::__construct
     */
    public function testGetFileAttributes()
    {
        $attribute1 = $this->createMock(Attribute::class);
        $attribute1->expects($this->once())->method('getType')->willReturn('file');
        $this->attributeFactory->expects($this->once())->method('create')
            ->willReturnOnConsecutiveCalls($attribute1);
        $instance = $this->getInstance(['_attributes' => [[]]]);
        $this->assertEquals([$attribute1], $instance->getFileAttributes());
        $this->assertTrue($instance->hasFileAttributes());
    }

    /**
     * @covers \App\Model\Entity::getParentCollectionModel
     * @covers \App\Model\Entity::__construct
     */
    public function testGetParentCollectionModel()
    {
        $this->assertEquals(
            'StoreAwareAbstractCollection',
            $this->getInstance(['store' => true])->getParentCollectionModel()
        );
        $this->assertEquals(
            'AbstractCollection',
            $this->getInstance(['store' => false])->getParentCollectionModel()
        );
    }

    /**
     * @covers \App\Model\Entity::getParentResourceModel
     * @covers \App\Model\Entity::__construct
     */
    public function testgetParentResourceModel()
    {
        $this->assertEquals(
            'StoreAwareAbstractModel',
            $this->getInstance(['store' => true])->getParentResourceModel()
        );
        $this->assertEquals(
            'AbstractModel',
            $this->getInstance(['store' => false])->getParentResourceModel()
        );
    }

    /**
     * @covers \App\Model\Entity::getAdminRoute
     * @covers \App\Model\Entity::__construct
     */
    public function testGetAdminRoute()
    {
        $this->module->expects($this->exactly(2))->method('getAdminRoutePrefix')->willReturn('module');
        $this->stringUtil->expects($this->exactly(2))->method('snake')->willReturnArgument(0);
        $instance = $this->getInstance(['name_singular' => 'name']);
        $this->assertEquals('module/name/', $instance->getAdminRoute(''));
        $this->assertEquals('module/name/suffix', $instance->getAdminRoute('suffix'));
    }

    /**
     * @covers \App\Model\Entity::getSaveDataProcessor
     * @covers \App\Model\Entity::__construct
     */
    public function testGetSaveDataProcessor()
    {
        $this->module->method('getProcessorTypes')->willReturn(['save']);
        $this->module->method('getModuleName')->willReturn('Md');
        $this->stringUtil->expects($this->once())->method('glueClassParts')->willReturnCallback(
            function ($data) {
                return implode('_', $data);
            }
        );
        $attribute1 = $this->createMock(Attribute::class);
        $attribute1->method('getProcessorTypes')->willReturnMap([
            ['save', ['processor']],
        ]);
        $this->attributeFactory->expects($this->once())->method('create')->willReturn($attribute1);
        $instance = $this->getInstance(['name_singular' => 'name', '_attributes' => [[]]]);
        $this->assertEquals(
            'Md_name_SaveDataProcessor',
            $instance->getSaveDataProcessor()
        );
    }

    /**
     * @covers \App\Model\Entity::getSaveDataProcessor
     * @covers \App\Model\Entity::__construct
     */
    public function testGetSaveDataProcessorNullProcessor()
    {
        $this->module->method('getNullSaveDataProcessor')->willReturn('NullProcessor');
        $instance = $this->getInstance(['name_singular' => 'name']);
        $this->assertEquals(
            'NullProcessor',
            $instance->getSaveDataProcessor()
        );
    }

    /**
     * @covers \App\Model\Entity::getFormDataModifier
     * @covers \App\Model\Entity::__construct
     */
    public function testGetFormDataModifier()
    {
        $this->module->method('getProcessorTypes')->willReturn(['provider']);
        $this->module->method('getModuleName')->willReturn('Md');
        $this->stringUtil->expects($this->once())->method('glueClassParts')->willReturnCallback(
            function ($data) {
                return implode('_', $data);
            }
        );
        $attribute1 = $this->createMock(Attribute::class);
        $attribute1->method('getProcessorTypes')->willReturnMap([
            ['provider', ['processor']],
        ]);
        $this->attributeFactory->expects($this->once())->method('create')->willReturn($attribute1);
        $instance = $this->getInstance(['name_singular' => 'name', '_attributes' => [[]]]);
        $this->assertEquals(
            'Md_name_FormDataModifier',
            $instance->getFormDataModifier()
        );
    }

    /**
     * @covers \App\Model\Entity::getFormDataModifier
     * @covers \App\Model\Entity::__construct
     */
    public function testgetFormDataModifierNullMOdifier()
    {
        $this->module->method('getNullFormDataModifier')->willReturn('NullModifier');
        $instance = $this->getInstance(['name_singular' => 'name']);
        $this->assertEquals(
            'NullModifier',
            $instance->getFormDataModifier()
        );
    }

    /**
     * @param $data
     * @return Entity
     */
    private function getInstance($data): Entity
    {
        return new Entity(
            $this->stringUtil,
            $this->attributeFactory,
            $this->module,
            $data
        );
    }
}
