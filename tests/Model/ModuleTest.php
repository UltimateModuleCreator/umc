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
     * setup tests
     */
    protected function setUp()
    {
        $this->module = new Module([], [], $this->data);
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
        $module = new Module(['php' => $php, 'xml' => $xml], []);
        $module->getFormattedLicense('php');
        $module->getFormattedLicense('xml');
    }

    /**
     * @covers \App\Model\Module::getFormattedLicense()
     * @covers \App\Model\Module::__construct()
     */
    public function testGetFormattedLicenseWithException()
    {
        $module = new Module(['wrong' => new \stdClass()], []);
        $this->expectException(\Exception::class);
        $module->getFormattedLicense('wrong');
    }

    /**
     * @covers \App\Model\Module::getFormattedLicense()
     * @covers \App\Model\Module::__construct()
     */
    public function testGetFormattedLicenseWithMissingProcessor()
    {
        $module = new Module([], []);
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
        $module = new Module([], []);
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

        $module = new Module([], []);
        $module->addEntity($entity1);
        $module->addEntity($entity2);
        $this->assertEquals([$entity1], $module->getSearchableEntities());

        $module = new Module([], []);
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

        $module = new Module([], []);
        $module->addEntity($entity1);
        $module->addEntity($entity2);
        $this->assertTrue($module->hasSearchableEntities());

        $module = new Module([], []);
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
        $module = new Module([], []);
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
                'parent' => 'level1'
            ],
            'level3' => [
                'label' => 'Level 3',
                'parent' => 'level2'
            ]
        ];
        $module = new Module([], $menuConfig, ['menu_parent' => '']);
        $this->assertEquals([], $module->getAclMenuParents());

        $module->setData('menu_parent', 'level1');
        $this->assertEquals(['level1'], $module->getAclMenuParents());

        $module->setData('menu_parent', 'level2');
        $this->assertEquals(['level1', 'level2'], $module->getAclMenuParents());

        $module->setData('menu_parent', 'level3');
        $this->assertEquals(['level1', 'level2', 'level3'], $module->getAclMenuParents());

        $module->setData('menu_parent', 'missing');
        $this->assertEquals(['missing'], $module->getAclMenuParents());
    }
}
