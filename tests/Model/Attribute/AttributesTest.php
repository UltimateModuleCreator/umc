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

class AttributesTest extends TestCase
{
    /**
     * @var \Twig_Environment | Attribute
     */
    private $twig;
    /**
     * @var Attribute | MockObject
     */
    private $attribute;

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->twig = $this->createMock(\Twig_Environment::class);
        $this->attribute = $this->createMock(Attribute::class);
    }

    /**
     * @covers \App\Model\Attribute\Attributes::getSourceModel
     */
    public function testGetSourceModel()
    {
        /** @var Entity | MockObject $entity */
        $entity = $this->createMock(Entity::class);
        /** @var Module | MockObject $module */
        $module = $this->createMock(Module::class);
        $this->attribute->method('getEntity')->willReturn($entity);
        $entity->method('getModule')->willReturn($module);
        $entity->method('getNameSingular')->willReturn('entityName');
        $module->method('getNamespace')->willReturn('Namespace');
        $module->method('getModuleName')->willReturn('ModuleName');
        $attributes = new Attribute\Attributes($this->twig, $this->attribute, []);
        $expected = 'Namespace\\ModuleName\\Model\\Source\\ProductAttribute';
        $this->assertEquals($expected, $attributes->getSourceModel());
    }

    /**
     * @covers \App\Model\Attribute\Attributes::getAttributeColumnSettingsString
     * @covers \App\Model\Attribute\Attributes::getAttributeColumnSettings
     */
    public function testGetAttributeColumnSettingsString()
    {
        $attributes = new Attribute\Attributes($this->twig, $this->attribute, []);
        $this->assertContains("'unsigned' => true", $attributes->getAttributeColumnSettingsString());
    }
}
