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

use App\Umc\CoreBundle\Model\Entity\Factory;
use App\Umc\CoreBundle\Util\StringUtil;
use App\Umc\MagentoBundle\Model\Entity;
use App\Umc\MagentoBundle\Model\Module;
use PHPUnit\Framework\TestCase;

class ModuleTest extends TestCase
{
    /**
     * @var StringUtil
     */
    private $stringUtil;
    /**
     * @var Factory
     */
    private $entityFactory;
    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->stringUtil = $this->createMock(StringUtil::class);
        $this->entityFactory = $this->createMock(Factory::class);
    }

    /**
     * @covers \App\Umc\MagentoBundle\Model\Module::isUmcCrud
     * @covers \App\Umc\MagentoBundle\Model\Module::__construct
     */
    public function testIsUmcCrud()
    {
        $this->assertTrue($this->getInstance(['umc_crud' => 1])->isUmcCrud());
        $this->assertFalse($this->getInstance([])->isUmcCrud());
    }

    /**
     * @covers \App\Umc\MagentoBundle\Model\Module::toArray
     * @covers \App\Umc\MagentoBundle\Model\Module::__construct
     */
    public function testToArray()
    {
        $this->assertArrayHasKey('umc_crud', $this->getInstance([])->toArray());
    }

    /**
     * @covers \App\Umc\MagentoBundle\Model\Module::getModuleDependencies
     * @covers \App\Umc\MagentoBundle\Model\Module::__construct
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
     * @covers \App\Umc\MagentoBundle\Model\Module::getModuleDependencies
     * @covers \App\Umc\MagentoBundle\Model\Module::__construct
     */
    public function testGetModuleDependenciesNoUmcCrud()
    {
        $entity1 = $this->createMock(Entity::class);
        $entity1->method('getModuleDependencies')->willReturn(['dep11', 'dep21']);
        $entity2 = $this->createMock(Entity::class);
        $entity2->method('getModuleDependencies')->willReturn(['dep11', 'dep31']);
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
        $this->assertTrue(in_array('dep11', $module->getModuleDependencies()));
        $this->assertTrue(in_array('dep21', $module->getModuleDependencies()));
        $this->assertTrue(in_array('dep31', $module->getModuleDependencies()));
    }

    /**
     * @covers \App\Umc\MagentoBundle\Model\Module::getComposerDependencies
     * @covers \App\Umc\MagentoBundle\Model\Module::__construct
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
     * @covers \App\Umc\MagentoBundle\Model\Module::getComposerDependencies
     * @covers \App\Umc\MagentoBundle\Model\Module::__construct
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
     * @covers \App\Umc\MagentoBundle\Model\Module::getComposerExtensionName
     * @covers \App\Umc\MagentoBundle\Model\Module::__construct
     */
    public function testGetComposerExtensionName()
    {
        $this->stringUtil->method('snake')->willReturnArgument(0);
        $this->stringUtil->method('camel')->willReturnArgument(0);
        $this->stringUtil->method('hyphen')->willReturnArgument(0);
        $this->stringUtil->method('ucfirst')->willReturnArgument(0);
        $this->assertEquals(
            'ns/module-md',
            $this->getInstance(['namespace' => 'ns', 'module_name' => 'md'])->getComposerExtensionName()
        );
    }

    /**
     * @covers \App\Umc\MagentoBundle\Model\Module::getAclName
     * @covers \App\Umc\MagentoBundle\Model\Module::__construct
     */
    public function testGetAclName()
    {
        $this->stringUtil->method('snake')->willReturnArgument(0);
        $this->stringUtil->method('camel')->willReturnArgument(0);
        $this->stringUtil->method('ucfirst')->willReturnArgument(0);
        $expected = 'ns_md::md';
        $data = [
            'namespace' => 'ns',
            'module_name' => 'md'
        ];
        $this->assertEquals($expected, $this->getInstance($data)->getAclName());
    }

    /**
     * @covers \App\Umc\MagentoBundle\Model\Module::getAclMenuParents
     * @covers \App\Umc\MagentoBundle\Model\Module::__construct
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
        $this->assertEquals([], $this->getInstance(['menu_parent' => ''], $menuConfig)->getAclMenuParents());

        $instance = $this->getInstance(['menu_parent' => 'level1'], $menuConfig);
        $this->assertEquals(['level1'], $instance->getAclMenuParents());
        //call twice for memoizing
        $this->assertEquals(['level1'], $instance->getAclMenuParents());

        $this->assertEquals(
            ['level1', 'level2'],
            $this->getInstance(['menu_parent' => 'level2'], $menuConfig)->getAclMenuParents()
        );
        $this->assertEquals(
            ['acl1', 'acl2'],
            $this->getInstance(['menu_parent' => 'level22'], $menuConfig)->getAclMenuParents()
        );
        $this->assertEquals(
            ['level1', 'level2', 'level3'],
            $this->getInstance(['menu_parent' => 'level3'], $menuConfig)->getAclMenuParents()
        );
        $this->assertEquals(
            ['missing'],
            $this->getInstance(['menu_parent' => 'missing'], $menuConfig)->getAclMenuParents()
        );
    }

    /**
     * @covers \App\Umc\MagentoBundle\Model\Module::getUmcCrudNamespace
     * @covers \App\Umc\MagentoBundle\Model\Module::__construct
     */
    public function testGetUmcCrudNamespace()
    {
        $this->stringUtil->method('snake')->willReturnArgument(0);
        $this->stringUtil->method('camel')->willReturnArgument(0);
        $this->stringUtil->method('ucfirst')->willReturnArgument(0);
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
     * @covers \App\Umc\MagentoBundle\Model\Module::getUmcModuleName
     * @covers \App\Umc\MagentoBundle\Model\Module::__construct
     */
    public function testGetUmcModuleName()
    {
        $this->stringUtil->method('snake')->willReturnArgument(0);
        $this->stringUtil->method('camel')->willReturnArgument(0);
        $this->stringUtil->method('ucfirst')->willReturnArgument(0);
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
     * @covers \App\Umc\MagentoBundle\Model\Module::getNullSaveDataProcessor
     * @covers \App\Umc\MagentoBundle\Model\Module::__construct
     */
    public function testGetNullSaveDataProcessor()
    {
        $this->stringUtil->method('snake')->willReturnArgument(0);
        $this->stringUtil->method('camel')->willReturnArgument(0);
        $this->stringUtil->method('ucfirst')->willReturnArgument(0);
        $expected = ['Umc', 'Crud', 'Ui', 'SaveDataProcessor', 'NullProcessor'];
        $this->stringUtil->expects($this->once())->method('glueClassParts')->with($expected)->willReturn('class');
        $this->assertEquals('class', $this->getInstance(['umc_crud' => 1])->getNullSaveDataProcessor());
    }

    /**
     * @covers \App\Umc\MagentoBundle\Model\Module::getNullSaveDataProcessor
     * @covers \App\Umc\MagentoBundle\Model\Module::__construct
     */
    public function testGetNullSaveDataProcessorNoUmcCrud()
    {
        $this->stringUtil->method('snake')->willReturnArgument(0);
        $this->stringUtil->method('camel')->willReturnArgument(0);
        $this->stringUtil->method('ucfirst')->willReturnArgument(0);
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
     * @covers \App\Umc\MagentoBundle\Model\Module::getNullFormDataModifier
     * @covers \App\Umc\MagentoBundle\Model\Module::__construct
     */
    public function testGetNullFormDataModifier()
    {
        $this->stringUtil->method('snake')->willReturnArgument(0);
        $this->stringUtil->method('camel')->willReturnArgument(0);
        $this->stringUtil->method('ucfirst')->willReturnArgument(0);
        $expected = ['Umc', 'Crud', 'Ui', 'Form', 'DataModifier', 'NullModifier'];
        $this->stringUtil->expects($this->once())->method('glueClassParts')->with($expected)->willReturn('class');
        $this->assertEquals('class', $this->getInstance(['umc_crud' => 1])->getNullFormDataModifier());
    }

    /**
     * @covers \App\Umc\MagentoBundle\Model\Module::getNullFormDataModifier
     * @covers \App\Umc\MagentoBundle\Model\Module::__construct
     */
    public function testGetNullFormDataModifierNoUmcCrud()
    {
        $this->stringUtil->method('snake')->willReturnArgument(0);
        $this->stringUtil->method('camel')->willReturnArgument(0);
        $this->stringUtil->method('ucfirst')->willReturnArgument(0);
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
     * @covers \App\Umc\MagentoBundle\Model\Module::getAdminRoutePrefix
     * @covers \App\Umc\MagentoBundle\Model\Module::__construct
     */
    public function testGetAdminRoutePrefix()
    {
        $this->stringUtil->method('snake')->willReturnArgument(0);
        $this->stringUtil->method('camel')->willReturnArgument(0);
        $this->stringUtil->method('ucfirst')->willReturnArgument(0);
        $this->assertEquals('modulename', $this->getInstance(['module_name' => 'module_name'])->getAdminRoutePrefix());
    }

    /**
     * @param $data
     * @param array $menuConfig
     * @return Module
     */
    private function getInstance($data, $menuConfig = []): Module
    {
        return new Module(
            $this->stringUtil,
            $this->entityFactory,
            $menuConfig,
            $data
        );
    }
}
