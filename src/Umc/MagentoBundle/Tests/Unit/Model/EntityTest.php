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

namespace App\Umc\MagentoBundle\Tests\Unit\Model;

use App\Umc\CoreBundle\Model\Attribute\Factory;
use App\Umc\CoreBundle\Util\StringUtil;
use App\Umc\MagentoBundle\Model\Attribute;
use App\Umc\MagentoBundle\Model\Entity;
use App\Umc\MagentoBundle\Model\Module;
use PHPUnit\Framework\TestCase;

class EntityTest extends TestCase
{
    /**
     * @var StringUtil
     */
    private $stringUtil;
    /**
     * @var Factory
     */
    private $attributeFactory;
    /**
     * @var Module
     */
    private $module;

    protected function setUp(): void
    {
        $this->stringUtil = $this->createMock(StringUtil::class);
        $this->attributeFactory = $this->createMock(Factory::class);
        $this->module = $this->createMock(Module::class);
    }

    /**
     * @covers \App\Umc\MagentoBundle\Model\Entity::getMainTableName
     * @covers \App\Umc\MagentoBundle\Model\Entity::__construct
     */
    public function testGetMainTableName()
    {
        $this->module->method('getModuleName')->willReturn('Md');
        $this->stringUtil->expects($this->once())->method('snake')->willReturnArgument(0);
        $expected = 'Md_name';
        $this->assertEquals($expected, $this->getInstance(['name_singular' => 'name'])->getMainTableName());
    }

    /**
     * @covers \App\Umc\MagentoBundle\Model\Entity::getStoreTableName
     * @covers \App\Umc\MagentoBundle\Model\Entity::__construct
     */
    public function testGetStoreTableName()
    {
        $this->module->method('getModuleName')->willReturn('Md');
        $this->stringUtil->expects($this->once())->method('snake')->willReturnArgument(0);
        $expected = 'Md_name_store';
        $this->assertEquals($expected, $this->getInstance(['name_singular' => 'name'])->getStoreTableName());
    }

    /**
     * @covers \App\Umc\MagentoBundle\Model\Entity::getComposerDependencies
     * @covers \App\Umc\MagentoBundle\Model\Entity::__construct
     */
    public function testGetComposerDependencies()
    {
        $this->module->method('isFrontend')->willReturn(true);
        $instance = $this->getInstance(['frontend' => true]);
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
        $attribute->method('getFlags')->willReturn(['product_attribute', 'upload']);
        $this->attributeFactory->expects($this->once())->method('create')->willReturn($attribute);
        $result = $this->getInstance(['frontend' => true, '_attributes' => [[]]])->getComposerDependencies();
        $this->assertTrue(in_array('magento/module-widget', $result));
        $this->assertTrue(in_array('magento/module-media-storage', $result));
        $this->assertTrue(in_array('magento/module-catalog', $result));
        $this->assertTrue(in_array('magento/module-eav', $result));
    }

    /**
     * @covers \App\Umc\MagentoBundle\Model\Entity::getModuleDependencies
     * @covers \App\Umc\MagentoBundle\Model\Entity::__construct
     */
    public function testGetModuleDependencies()
    {
        $this->module->method('isFrontend')->willReturn(true);
        $instance = $this->getInstance(['frontend' => true]);
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
        $attribute->method('getFlags')->willReturn(['product_attribute', 'upload']);
        $this->attributeFactory->expects($this->once())->method('create')->willReturn($attribute);
        $result = $this->getInstance(['frontend' => true, '_attributes' => [[]]])->getModuleDependencies();
        $this->assertTrue(in_array('Magento_Widget', $result));
        $this->assertTrue(in_array('Magento_MediaStorage', $result));
        $this->assertTrue(in_array('Magento_Catalog', $result));
        $this->assertTrue(in_array('Magento_Eav', $result));
    }

    /**
     * @covers \App\Umc\MagentoBundle\Model\Entity::getAclName
     * @covers \App\Umc\MagentoBundle\Model\Entity::__construct
     */
    public function testGetAclName()
    {
        $this->module->method('getAclName')->willReturn('acl_name');
        $this->stringUtil->expects($this->once())->method('snake')->willReturnArgument(0);
        $expected = 'acl_name_name';
        $this->assertEquals($expected, $this->getInstance(['name_singular' => 'name'])->getAclName());
    }

    /**
     * @covers \App\Umc\MagentoBundle\Model\Entity::getModel
     * @covers \App\Umc\MagentoBundle\Model\Entity::__construct
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
     * @covers \App\Umc\MagentoBundle\Model\Entity::getModel
     * @covers \App\Umc\MagentoBundle\Model\Entity::__construct
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
     * @covers \App\Umc\MagentoBundle\Model\Entity::getAdminController
     * @covers \App\Umc\MagentoBundle\Model\Entity::__construct
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
     * @covers \App\Umc\MagentoBundle\Model\Entity::getResourceModel
     * @covers \App\Umc\MagentoBundle\Model\Entity::__construct
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
     * @covers \App\Umc\MagentoBundle\Model\Entity::getCollectionModel
     * @covers \App\Umc\MagentoBundle\Model\Entity::__construct
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
     * @covers \App\Umc\MagentoBundle\Model\Entity::getRepoModel
     * @covers \App\Umc\MagentoBundle\Model\Entity::__construct
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
     * @covers \App\Umc\MagentoBundle\Model\Entity::getListRepoModel
     * @covers \App\Umc\MagentoBundle\Model\Entity::__construct
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
     * @covers \App\Umc\MagentoBundle\Model\Entity::getSearchModel
     * @covers \App\Umc\MagentoBundle\Model\Entity::__construct
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
     * @covers \App\Umc\MagentoBundle\Model\Entity::getInterface
     * @covers \App\Umc\MagentoBundle\Model\Entity::__construct
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
     * @covers \App\Umc\MagentoBundle\Model\Entity::getRepoInterface
     * @covers \App\Umc\MagentoBundle\Model\Entity::__construct
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
     * @covers \App\Umc\MagentoBundle\Model\Entity::getListRepoInterface
     * @covers \App\Umc\MagentoBundle\Model\Entity::__construct
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
     * @covers \App\Umc\MagentoBundle\Model\Entity::getSearchResultsInterface
     * @covers \App\Umc\MagentoBundle\Model\Entity::__construct
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
     * @covers \App\Umc\MagentoBundle\Model\Entity::getPk
     * @covers \App\Umc\MagentoBundle\Model\Entity::__construct
     */
    public function testGetPk()
    {
        $this->stringUtil->method('snake')->willReturnArgument(0);
        $this->assertEquals('name_id', $this->getInstance(['name_singular' => 'name'])->getPk());
    }

    /**
     * @covers \App\Umc\MagentoBundle\Model\Entity::getUploadFolder
     * @covers \App\Umc\MagentoBundle\Model\Entity::__construct
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
     * @covers \App\Umc\MagentoBundle\Model\Entity::getUploadInfoModel
     * @covers \App\Umc\MagentoBundle\Model\Entity::__construct
     */
    public function testGetUploadInfoModel()
    {
        $instance = $this->getInstance(['name_singular' => 'name']);
        $this->module->method('getModuleName')->willReturn('Md');
        $this->stringUtil->method('glueClassParts')->with(['Md', 'name', 'type', 'Info'])->willReturn('model');
        $this->assertEquals('model', $instance->getUploadInfoModel('type'));
    }

    /**
     * @covers \App\Umc\MagentoBundle\Model\Entity::getStoreHandler
     * @covers \App\Umc\MagentoBundle\Model\Entity::__construct
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
     * @covers \App\Umc\MagentoBundle\Model\Entity::getVirtualType
     * @covers \App\Umc\MagentoBundle\Model\Entity::__construct
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
     * @covers \App\Umc\MagentoBundle\Model\Entity::getEventPrefix
     * @covers \App\Umc\MagentoBundle\Model\Entity::__construct
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
     * @covers \App\Umc\MagentoBundle\Model\Entity::getSaveDataProcessor
     * @covers \App\Umc\MagentoBundle\Model\Entity::__construct
     */
    public function testGetSaveDataProcessor()
    {
        $this->module->method('getModuleName')->willReturn('Md');
        $this->stringUtil->expects($this->once())->method('glueClassParts')->willReturnCallback(
            function ($data) {
                return implode('_', $data);
            }
        );
        $attribute1 = $this->createMock(Attribute::class);
        $attribute1->method('getFlags')->willReturn(['processor_save']);
        $this->attributeFactory->expects($this->once())->method('create')->willReturn($attribute1);
        $instance = $this->getInstance(['name_singular' => 'name', '_attributes' => [[]]]);
        $this->assertEquals(
            'Md_name_SaveDataProcessor',
            $instance->getSaveDataProcessor()
        );
    }

    /**
     * @covers \App\Umc\MagentoBundle\Model\Entity::getSaveDataProcessorInlineEdit
     * @covers \App\Umc\MagentoBundle\Model\Entity::__construct
     */
    public function testGetSaveDataProcessorInlineEdit()
    {
        $this->module->method('getModuleName')->willReturn('Md');
        $this->stringUtil->expects($this->once())->method('glueClassParts')->willReturnCallback(
            function ($data) {
                return implode('_', $data);
            }
        );
        $attribute1 = $this->createMock(Attribute::class);
        $attribute1->method('getFlags')->willReturn(['processor_inline_edit']);
        $this->attributeFactory->expects($this->once())->method('create')->willReturn($attribute1);
        $instance = $this->getInstance(['name_singular' => 'name', '_attributes' => [[]]]);
        $this->assertEquals(
            'Md_name_SaveDataProcessorInlineEdit',
            $instance->getSaveDataProcessorInlineEdit()
        );
    }

    /**
     * @covers \App\Umc\MagentoBundle\Model\Entity::getSaveDataProcessor
     * @covers \App\Umc\MagentoBundle\Model\Entity::__construct
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
     * @covers \App\Umc\MagentoBundle\Model\Entity::getFormDataModifier
     * @covers \App\Umc\MagentoBundle\Model\Entity::__construct
     */
    public function testGetFormDataModifier()
    {
        $this->module->method('getModuleName')->willReturn('Md');
        $this->stringUtil->expects($this->once())->method('glueClassParts')->willReturnCallback(
            function ($data) {
                return implode('_', $data);
            }
        );
        $attribute1 = $this->createMock(Attribute::class);
        $attribute1->method('getFlags')->willReturn(['processor_provider']);
        $this->attributeFactory->expects($this->once())->method('create')->willReturn($attribute1);
        $instance = $this->getInstance(['name_singular' => 'name', '_attributes' => [[]]]);
        $this->assertEquals(
            'Md_name_FormDataModifier',
            $instance->getFormDataModifier()
        );
    }

    /**
     * @covers \App\Umc\MagentoBundle\Model\Entity::getFormDataModifier
     * @covers \App\Umc\MagentoBundle\Model\Entity::__construct
     */
    public function testGetFormDataModifierNullModifier()
    {
        $this->module->method('getNullFormDataModifier')->willReturn('NullModifier');
        $instance = $this->getInstance(['name_singular' => 'name']);
        $this->assertEquals(
            'NullModifier',
            $instance->getFormDataModifier()
        );
    }

    /**
     * @covers \App\Umc\MagentoBundle\Model\Entity::getAdminRoute
     * @covers \App\Umc\MagentoBundle\Model\Entity::__construct
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
     * @covers \App\Umc\MagentoBundle\Model\Entity::getFrontendRoute
     * @covers \App\Umc\MagentoBundle\Model\Entity::__construct
     */
    public function testGetFrontendRoute()
    {
        $this->module->expects($this->exactly(2))->method('getFrontKey')->willReturn('module');
        $this->stringUtil->expects($this->exactly(2))->method('snake')->willReturnArgument(0);
        $instance = $this->getInstance(['name_singular' => 'name']);
        $this->assertEquals('module/name/', $instance->getFrontendRoute(''));
        $this->assertEquals('module/name/suffix', $instance->getFrontendRoute('suffix'));
    }

    /**
     * @covers \App\Umc\MagentoBundle\Model\Entity::getParentResourceModel
     * @covers \App\Umc\MagentoBundle\Model\Entity::__construct
     */
    public function testGetParentResourceModel()
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
     * @covers \App\Umc\MagentoBundle\Model\Entity::getParentCollectionModel
     * @covers \App\Umc\MagentoBundle\Model\Entity::__construct
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
