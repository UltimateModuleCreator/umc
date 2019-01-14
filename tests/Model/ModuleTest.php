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

use App\Model\Entity;
use App\Model\Module;
use App\Service\License\ProcessorInterface;
use App\Util\StringUtil;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class ModuleTest extends TestCase
{
    /**
     * @var Module
     */
    private $module;
    /**
     * @var array
     */
    private $data = [
        'namespace' => 'Namespace',
        'module_name' => 'Module',
        'dummy' => 'dummy'
    ];
    /**
     * @var StringUtil | MockObject
     */
    private $stringUtil;

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->stringUtil = $this->createMock(StringUtil::class);
        $this->module = new Module($this->stringUtil, [], [], $this->data);
    }

    /**
     * @covers \App\Model\Module::getData()
     * @covers \App\Model\Module::__construct()
     */
    public function testGetData()
    {
        $this->assertEquals('Namespace', $this->module->getData('namespace'));
        $this->assertEquals('dummy', $this->module->getData('dummy'));
        $this->assertNull($this->module->getData('non_existent'));
        $this->assertEquals('default', $this->module->getData('non_existent', 'default'));
    }

    /**
     * @covers \App\Model\Module::getRawData()
     * @covers \App\Model\Module::__construct()
     */
    public function testGetRawData()
    {
        $this->assertEquals($this->data, $this->module->getRawData());
    }

    /**
     * @covers \App\Model\Module::getPropertiesData()
     * @covers \App\Model\Module::__construct()
     */
    public function testGetPropertiesData()
    {
        $this->assertArrayNotHasKey('dummy', $this->module->getPropertiesData());
    }

    /**
     * @covers \App\Model\Module::toArray()
     * @covers \App\Model\Module::getAdditionalToArray()
     * @covers \App\Model\Module::__construct()
     */
    public function testToArray()
    {
        /** @var Entity | MockObject $entity */
        $entity = $this->createMock(Entity::class);
        $entity->expects($this->once())->method('toArray')->willReturn(['entity']);
        $this->module->addEntity($entity);
        $moduleArray = $this->module->toArray();
        $this->assertArrayHasKey('_entities', $moduleArray);
        $this->assertArrayHasKey('menu_text', $moduleArray);
        $this->assertArrayNotHasKey('dummy', $moduleArray);
    }

    /**
     * @covers \App\Model\Module::getFormattedLicense()
     * @covers \App\Model\Module::__construct()
     * @throws \Exception
     */
    public function testGetFormattedLicense()
    {
        $php = $this->createMock(ProcessorInterface::class);
        $xml = $this->createMock(ProcessorInterface::class);
        $php->expects($this->once())->method('process');
        $xml->expects($this->once())->method('process');
        $module = new Module($this->stringUtil, ['php' => $php, 'xml' => $xml], []);
        $module->getFormattedLicense('php');
        $module->getFormattedLicense('xml');
    }

    /**
     * @covers \App\Model\Module::getFormattedLicense()
     * @covers \App\Model\Module::__construct()
     */
    public function testGetFormattedLicenseWithException()
    {
        $module = new Module($this->stringUtil, ['wrong' => new \stdClass()], []);
        $this->expectException(\Exception::class);
        $module->getFormattedLicense('wrong');
    }

    /**
     * @covers \App\Model\Module::getFormattedLicense()
     * @covers \App\Model\Module::__construct()
     */
    public function testGetFormattedLicenseWithMissingProcessor()
    {
        $module = new Module($this->stringUtil, [], []);
        $this->expectException(\Exception::class);
        $module->getFormattedLicense('missing');
    }

    /**
     * @covers \App\Model\Module::hasAttributeType()
     * @covers \App\Model\Module::__construct()
     */
    public function testHasAttributeType()
    {
        /** @var Entity | MockObject $entity1 */
        $entity1 = $this->createMock(Entity::class);
        $entity1->method('hasAttributeType')->willReturnMap([
            ['text', true],
            ['textarea', false],
            ['dummy', false]
        ]);
        /** @var Entity | MockObject $entity2 */
        $entity2 = $this->createMock(Entity::class);
        $entity2->method('hasAttributeType')->willReturnMap([
            ['text', false],
            ['textarea', true],
            ['dummy', false]
        ]);
        $module = new Module($this->stringUtil, [], []);
        $module->addEntity($entity1);
        $module->addEntity($entity2);
        $this->assertTrue($module->hasAttributeType('text'));
        $this->assertTrue($module->hasAttributeType('textarea'));
        $this->assertFalse($module->hasAttributeType('dummy'));
    }

    /**
     * @covers \App\Model\Module::getSearchableEntities()
     * @covers \App\Model\Module::__construct()
     */
    public function testGetSearchableEntities()
    {
        /** @var Entity | MockObject $entity1 */
        $entity1 = $this->createMock(Entity::class);
        $entity1->method('getData')->willReturn('search')->willReturn("1");
        /** @var Entity | MockObject $entity2 */
        $entity2 = $this->createMock(Entity::class);
        $entity2->method('getData')->willReturn('search')->willReturn("0");

        $module = new Module($this->stringUtil, [], []);
        $module->addEntity($entity1);
        $module->addEntity($entity2);
        $this->assertEquals([$entity1], $module->getSearchableEntities());

        $module = new Module($this->stringUtil, [], []);
        $module->addEntity($entity2);
        $this->assertEquals([], $module->getSearchableEntities());
    }

    /**
     * @covers \App\Model\Module::hasSearchableEntities()
     * @covers \App\Model\Module::__construct()
     */
    public function testHasSearchableEntities()
    {
        /** @var Entity | MockObject $entity1 */
        $entity1 = $this->createMock(Entity::class);
        $entity1->method('getData')->willReturn('search')->willReturn("1");
        /** @var Entity | MockObject $entity2 */
        $entity2 = $this->createMock(Entity::class);
        $entity2->method('getData')->willReturn('search')->willReturn("0");

        $module = new Module($this->stringUtil, [], []);
        $module->addEntity($entity1);
        $module->addEntity($entity2);
        $this->assertTrue($module->hasSearchableEntities());

        $module = new Module($this->stringUtil, [], []);
        $module->addEntity($entity2);
        $this->assertFalse($module->hasSearchableEntities());
    }

    /**
     * @covers \App\Model\Module::getExtensionName
     * @covers \App\Model\Module::__construct()
     */
    public function testGetExtensionName()
    {
        $module = new Module(
            $this->stringUtil,
            [],
            [],
            [
                'namespace' => 'Namespace',
                'module_name' => 'ModuleName'
            ]
        );
        $this->assertEquals('Namespace_ModuleName', $module->getExtensionName());
    }

    /**
     * @covers \App\Model\Module::getEntities
     * @covers \App\Model\Module::addEntity
     * @covers \App\Model\Module::__construct()
     */
    public function testGetEntities()
    {
        $module = new Module($this->stringUtil, [], []);
        /** @var Entity | MockObject $entity1 */
        $entity1 = $this->createMock(Entity::class);
        /** @var Entity | MockObject $entity2 */
        $entity2 = $this->createMock(Entity::class);
        $module->addEntity($entity1);
        $module->addEntity($entity2);
        $entities = $module->getEntities();
        $this->assertEquals(2, count($entities));
        $this->assertEquals($entity1, $entities[0]);
        $this->assertEquals($entity2, $entities[1]);
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
        $module = new Module($this->stringUtil, [], $menuConfig, ['menu_parent' => '']);
        $this->assertEquals([], $module->getAclMenuParents());

        $module->setData('menu_parent', 'level1');
        $this->assertEquals(['level1'], $module->getAclMenuParents());

        $module->setData('menu_parent', 'level2');
        $this->assertEquals(['level1', 'level2'], $module->getAclMenuParents());

        $module->setData('menu_parent', 'level22');
        $this->assertEquals(['acl1', 'acl2'], $module->getAclMenuParents());

        $module->setData('menu_parent', 'level3');
        $this->assertEquals(['level1', 'level2', 'level3'], $module->getAclMenuParents());

        $module->setData('menu_parent', 'missing');
        $this->assertEquals(['missing'], $module->getAclMenuParents());
    }

    /**
     * @covers \App\Model\Module::getNamespace()
     * @covers \App\Model\Module::__construct()
     */
    public function testGetNamespace()
    {
        $module = new Module($this->stringUtil, [], [], ['namespace' => 'Namespace']);
        $this->assertEquals('Namespace', $module->getNamespace());
        $module = new Module($this->stringUtil, [], [], []);
        $this->assertEquals('', $module->getNamespace());
    }

    /**
     * @covers \App\Model\Module::getModuleName()
     * @covers \App\Model\Module::__construct()
     */
    public function testGetModuleName()
    {
        $module = new Module($this->stringUtil, [], [], ['module_name' => 'ModuleName']);
        $this->assertEquals('ModuleName', $module->getModuleName());
        $module = new Module($this->stringUtil, [], [], []);
        $this->assertEquals('', $module->getModuleName());
    }

    /**
     * @covers \App\Model\Module::getVersion()
     * @covers \App\Model\Module::__construct()
     */
    public function testGetVersion()
    {
        $module = new Module($this->stringUtil, [], [], ['version' => '1.1.1']);
        $this->assertEquals('1.1.1', $module->getVersion());
        $module = new Module($this->stringUtil, [], [], []);
        $this->assertEquals('', $module->getVersion());
    }

    /**
     * @covers \App\Model\Module::getMenuParent()
     * @covers \App\Model\Module::__construct()
     */
    public function testGetMenuParent()
    {
        $module = new Module($this->stringUtil, [], [], ['menu_parent' => 'parent']);
        $this->assertEquals('parent', $module->getMenuParent());
        $module = new Module($this->stringUtil, [], [], []);
        $this->assertEquals('', $module->getMenuParent());
    }

    /**
     * @covers \App\Model\Module::getSortOrder()
     * @covers \App\Model\Module::__construct()
     */
    public function testGetSortOrder()
    {
        $module = new Module($this->stringUtil, [], [], ['sort_order' => '55']);
        $this->assertEquals(55, $module->getSortOrder());
        $module = new Module($this->stringUtil, [], [], []);
        $this->assertEquals(0, $module->getSortOrder());
    }

    /**
     * @covers \App\Model\Module::getMenuText()
     * @covers \App\Model\Module::__construct()
     */
    public function testGetMenuText()
    {
        $module = new Module($this->stringUtil, [], [], ['menu_text' => 'Menu']);
        $this->assertEquals('Menu', $module->getMenuText());
        $module = new Module($this->stringUtil, [], [], []);
        $this->assertEquals('', $module->getMenuText());
    }

    /**
     * @covers \App\Model\Module::getLicense()
     * @covers \App\Model\Module::__construct()
     */
    public function testGetLicense()
    {
        $module = new Module($this->stringUtil, [], [], ['license' => 'License']);
        $this->assertEquals('License', $module->getLicense());
        $module = new Module($this->stringUtil, [], [], []);
        $this->assertEquals('', $module->getLicense());
    }

    /**
     * @covers \App\Model\Module::getFrontKey()
     * @covers \App\Model\Module::__construct()
     */
    public function testGetFrontKey()
    {
        $this->stringUtil->method('snake')->willReturnArgument(0);
        $module = new Module($this->stringUtil, [], [], ['namespace' => 'namespace', 'module_name' => 'module']);
        $this->assertEquals('namespace_module', $module->getFrontKey());
        $module = new Module($this->stringUtil, [], [], ['front_key' => 'front_key']);
        $this->assertEquals('front_key', $module->getFrontKey());
    }

    /**
     * @covers \App\Model\Module::getConfigTab()
     * @covers \App\Model\Module::__construct()
     */
    public function testGetConfigTab()
    {
        $module = new Module($this->stringUtil, [], [], ['config_tab' => 'Config Tab']);
        $this->assertEquals('Config Tab', $module->getConfigTab());
        $module = new Module($this->stringUtil, [], [], ['module_name' => 'Module Name']);
        $this->assertEquals('Module Name', $module->getConfigTab());
    }

    /**
     * @covers \App\Model\Module::getConfigTabPosition()
     * @covers \App\Model\Module::__construct()
     */
    public function testGetConfigTabPosition()
    {
        $module = new Module($this->stringUtil, [], [], ['config_tab_position' => 100]);
        $this->assertEquals(100, $module->getConfigTabPosition());
        $module = new Module($this->stringUtil, [], [], []);
        $this->assertEquals(0, $module->getConfigTabPosition());
    }

    /**
     * @covers \App\Model\Module::hasFrontend
     * @covers \App\Model\Module::__construct
     */
    public function testHasFrontend()
    {
        $module = new Module($this->stringUtil, [], [], []);
        $this->assertFalse($module->hasFrontend());
        /** @var Entity | MockObject $entity */
        $entity = $this->createMock(Entity::class);
        $entity->method('getData')->willReturnMap([
            ['frontend_view', null, "0"],
            ['frontend_list', null, "0"],
        ]);
        $module->addEntity($entity);
        $this->assertFalse($module->hasFrontend());
        /** @var Entity | MockObject $entity1 */
        $entity1 = $this->createMock(Entity::class);
        $entity1->method('getData')->willReturnMap([
            ['frontend_view', null, "1"],
            ['frontend_list', null, "0"],
        ]);
        $module->addEntity($entity1);
        $this->assertTrue($module->hasFrontend());
    }

    /**
     * @covers \App\Model\Module::getEntitiesWithProperty
     * @covers \App\Model\Module::__construct
     */
    public function testGetEntitiesWithProperty()
    {
        $module = new Module($this->stringUtil, [], [], []);
        /** @var Entity | MockObject $entity1 */
        $entity1 = $this->createMock(Entity::class);
        $entity1->method('getData')->willReturnMap([
            ['prop1', null, "1"],
            ['prop2', null, "0"],
        ]);
        /** @var Entity | MockObject $entity2 */
        $entity2 = $this->createMock(Entity::class);
        $entity2->method('getData')->willReturnMap([
            ['prop1', null, "0"],
            ['prop2', null, "1"],
        ]);
        $module->addEntity($entity1);
        $module->addEntity($entity2);
        $this->assertEquals([$entity1], $module->getEntitiesWithProperty('prop1'));
        $this->assertEquals([$entity2], $module->getEntitiesWithProperty('prop2'));
        $this->assertEquals([], $module->getEntitiesWithProperty('prop3'));
    }

    /**
     * @covers \App\Model\Module::getMagentoVersion()
     * @covers \App\Model\Module::__construct()
     */
    public function testGetMagentoVersion()
    {
        $module = new Module($this->stringUtil, [], [], ['magento_version' => '2.2']);
        $this->assertEquals(2.2, $module->getMagentoVersion());
        $module = new Module($this->stringUtil, [], [], ['magento_version' => '2.3']);
        $this->assertEquals(2.3, $module->getMagentoVersion());
        $module = new Module($this->stringUtil, [], [], []);
        $this->assertEquals(2.2, $module->getMagentoVersion());
    }

    /**
     * @covers \App\Model\Module::getMenuEntities
     * @covers \App\Model\Module::__construct
     */
    public function testGetMenuEntities()
    {
        $module = new Module($this->stringUtil, [], []);
        /** @var Entity | MockObject $entity1 */
        $entity1 = $this->createMock(Entity::class);
        $entity1->method('getMenuLink')->willReturn(1);
        /** @var Entity | MockObject $entity2 */
        $entity2 = $this->createMock(Entity::class);
        $entity2->method('getMenuLink')->willReturn(2);
        $module->addEntity($entity1);
        $module->addEntity($entity2);
        $this->assertEquals([$entity1], $module->getMenuEntities(1));
        $this->assertEquals([$entity2], $module->getMenuEntities(2));
        $this->assertEquals([], $module->getMenuEntities(3));
    }

    /**
     * @covers \App\Model\Module::hasTopMenu
     * @covers \App\Model\Module::__construct
     */
    public function testHasTopMenu()
    {
        $module = new Module($this->stringUtil, [], []);
        /** @var Entity | MockObject $entity1 */
        $entity1 = $this->createMock(Entity::class);
        $module->addEntity($entity1);
        $this->assertFalse($module->hasTopMenu());
        /** @var Entity | MockObject $entity2 */
        $entity2 = $this->createMock(Entity::class);
        $entity2->method('getMenuLink')->willReturn(Entity::MENU_LINK_MAIN_MENU);
        $module->addEntity($entity2);
        $this->assertTrue($module->hasTopMenu());
    }

    /**
     * @covers \App\Model\Module::hasFooterMenu
     * @covers \App\Model\Module::__construct
     */
    public function testHasFooterMenu()
    {
        $module = new Module($this->stringUtil, [], []);
        /** @var Entity | MockObject $entity1 */
        $entity1 = $this->createMock(Entity::class);
        $module->addEntity($entity1);
        $this->assertFalse($module->hasFooterMenu());
        /** @var Entity | MockObject $entity2 */
        $entity2 = $this->createMock(Entity::class);
        $entity2->method('getMenuLink')->willReturn(Entity::MENU_LINK_FOOTER);
        $module->addEntity($entity2);
        $this->assertTrue($module->hasFooterMenu());
    }
}
