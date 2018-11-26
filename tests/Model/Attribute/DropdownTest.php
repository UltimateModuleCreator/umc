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
namespace App\Tests\Attribute;

use App\Model\Attribute;
use App\Model\Entity;
use App\Model\Module;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DropdownTest extends TestCase
{
    /**
     * @covers \App\Model\Attribute\Dropdown::getPropertyNames
     */
    public function testGetPropertyNames()
    {
        /** @var \Twig_Environment | MockObject $twig */
        $twig = $this->createMock(\Twig_Environment::class);
        /** @var Attribute | MockObject $attribute */
        $attribute = $this->createMock(Attribute::class);
        $dropdown = new Attribute\Dropdown($twig, $attribute, []);
        $this->assertArrayHasKey('multiple_text', $dropdown->getPropertiesData());
    }

    /**
     * @covers \App\Model\Attribute\Dropdown::getSourceModel
     * @covers \App\Model\Attribute\Dropdown::camelize
     */
    public function testGetSourceModel()
    {
        /** @var \Twig_Environment | MockObject $twig */
        $twig = $this->createMock(\Twig_Environment::class);
        /** @var Attribute | MockObject $attribute */
        $attribute = $this->createMock(Attribute::class);
        /** @var Entity | MockObject $entity */
        $entity = $this->createMock(Entity::class);
        /** @var Module | MockObject $module */
        $module = $this->createMock(Module::class);
        $attribute->method('getEntity')->willReturn($entity);
        $attribute->method('getData')->willReturnMap([
            [
                'code',
                null,
                'attribute_code'
            ]
        ]);
        $entity->method('getModule')->willReturn($module);
        $entity->method('getData')->willReturnMap([
            [
                'name_singular',
                null,
                'entityName'
            ]
        ]);
        $module->method('getData')->willReturnMap(
            [
                [
                    'namespace',
                    null,
                    'Namespace'
                ],
                [
                    'module_name',
                    null,
                    'ModuleName'
                ]
            ]
        );
        $dropdown = new Attribute\Dropdown($twig, $attribute, []);
        $expected = 'Namespace\\ModuleName\\Model\\EntityName\\Source\\AttributeCode';
        $this->assertEquals($expected, $dropdown->getSourceModel());
    }
}
