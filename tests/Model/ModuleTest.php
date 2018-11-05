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
        $this->module = new Module($this->data);
    }

    /**
     * @covers \App\Model\Module::getData()
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
     */
    public function testGetRawData()
    {
        $this->assertEquals($this->data, $this->module->getRawData());
    }

    /**
     * @covers \App\Model\Module::getPropertiesData()
     */
    public function testGetPropertiesData()
    {
        $this->assertArrayNotHasKey('dummy', $this->module->getPropertiesData());
    }

    /**
     * @covers \App\Model\Module::toArray()
     * @covers \App\Model\Module::getAdditionalToArray()
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
     * @covers \App\Model\Module::getProcessedLicense()
     */
    public function testGetProcessedLicense()
    {
        $module = new Module([
            'namespace' => 'Namespace',
            'module_name' => 'ModuleName',
            'license' => "This is the license for {{Namespace}}_{{Module}} for the year {{Y}}\n" .
                "and {{this should not be replaced}}"
        ]);
        $expected = "This is the license for Namespace_ModuleName for the year " . date('Y') . "\n" .
            "and {{this should not be replaced}}";
        $this->assertEquals($expected, $module->getProcessedLicense());
    }

    /**
     * @covers \App\Model\Module::getProcessedLicense()
     */
    public function testGetProcessedLicenseEmpty()
    {
        $module = new Module([
            'namespace' => 'Namespace',
            'module_name' => 'ModuleName',
            'license' => "    "
        ]);
        $this->assertEquals('', $module->getProcessedLicense());
    }

    /**
     * @covers \App\Model\Module::getFormattedLicense()
     * @covers \App\Model\Module::formatPhpLicense()
     * @covers \App\Model\Module::formatXmlLicense()
     * @throws \Exception
     */
    public function testGetFormattedLicense()
    {
        $module = new Module([
            'namespace' => 'Namespace',
            'module_name' => 'ModuleName',
            'license' => "<!---->This is the license for {{Namespace}}_{{Module}} for the year {{Y}}\n*//*" .
                "and {{this should not be replaced}}"
        ]);
        $expected = PHP_EOL . "/**" . PHP_EOL;
        $expected .= " * This is the license for Namespace_ModuleName for the year " . date('Y') . PHP_EOL;
        $expected .= " * and {{this should not be replaced}}" . PHP_EOL;
        $expected .= " */" . PHP_EOL;
        $this->assertEquals($expected, $module->getFormattedLicense('php'));

        $expected = PHP_EOL . "<!--" . PHP_EOL;
        $expected .= "/**" . PHP_EOL;
        $expected .= " * This is the license for Namespace_ModuleName for the year " . date('Y') . PHP_EOL;
        $expected .= " * and {{this should not be replaced}}" . PHP_EOL;
        $expected .= " */" . PHP_EOL;
        $expected .= "-->" . PHP_EOL;
        $this->assertEquals($expected, $module->getFormattedLicense('xml'));
    }

    /**
     * @covers \App\Model\Module::getFormattedLicense
     */
    public function testGetFormatterLicenseNoLicense()
    {
        $module = new Module();
        $this->assertEmpty($module->getFormattedLicense('php'));
    }

    /**
     * @covers \App\Model\Module::getFormattedLicense()
     * @throws \Exception
     */
    public function testGetFormattedLicenseWithException()
    {
        $module = new Module([
            'namespace' => 'Namespace',
            'module_name' => 'ModuleName',
            'license' => "This is the license for {{Namespace}}_{{Module}} for the year {{Y}}\n" .
                "and {{this should not be replaced}}"
        ]);
        $this->expectException(\Exception::class);
        $module->getFormattedLicense('dummy');
    }

    /**
     * @covers \App\Model\Module::hasAttributeType()
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
        $module = new Module();
        $module->addEntity($entity1);
        $module->addEntity($entity2);
        $this->assertTrue($module->hasAttributeType('text'));
        $this->assertTrue($module->hasAttributeType('textarea'));
        $this->assertFalse($module->hasAttributeType('dummy'));
    }

    /**
     * @covers \App\Model\Module::getSearchableEntities()
     */
    public function testGetSearchableEntities()
    {
        /** @var Entity | MockObject $entity1 */
        $entity1 = $this->createMock(Entity::class);
        $entity1->method('getData')->willReturn('search')->willReturn("1");
        /** @var Entity | MockObject $entity2 */
        $entity2 = $this->createMock(Entity::class);
        $entity2->method('getData')->willReturn('search')->willReturn("0");

        $module = new Module();
        $module->addEntity($entity1);
        $module->addEntity($entity2);
        $this->assertEquals([$entity1], $module->getSearchableEntities());

        $module = new Module();
        $module->addEntity($entity2);
        $this->assertEquals([], $module->getSearchableEntities());
    }

    /**
     * @covers \App\Model\Module::hasSearchableEntities()
     */
    public function testHasSearchableEntities()
    {
        /** @var Entity | MockObject $entity1 */
        $entity1 = $this->createMock(Entity::class);
        $entity1->method('getData')->willReturn('search')->willReturn("1");
        /** @var Entity | MockObject $entity2 */
        $entity2 = $this->createMock(Entity::class);
        $entity2->method('getData')->willReturn('search')->willReturn("0");

        $module = new Module();
        $module->addEntity($entity1);
        $module->addEntity($entity2);
        $this->assertTrue($module->hasSearchableEntities());

        $module = new Module();
        $module->addEntity($entity2);
        $this->assertFalse($module->hasSearchableEntities());
    }

    /**
     * @covers \App\Model\Module::getExtensionName
     */
    public function testGetExtensionName()
    {
        $module = new Module([
            'namespace' => 'Namespace',
            'module_name' => 'ModuleName'
        ]);
        $this->assertEquals('Namespace_ModuleName', $module->getExtensionName());
    }

    /**
     * @covers \App\Model\Module::getEntities
     * @covers \App\Model\Module::addEntity
     */
    public function testGetEntities()
    {
        $module = new Module();
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
}
