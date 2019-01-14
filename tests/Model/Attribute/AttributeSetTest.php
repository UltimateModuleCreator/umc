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

class AttributeSetTest extends TestCase
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
     * @covers \App\Model\Attribute\AttributeSet::getSourceModel
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
        $attributeSet = new Attribute\AttributeSet($this->twig, $this->attribute, []);
        $expected = 'Namespace\\ModuleName\\Model\\Source\\ProductAttributeSet';
        $this->assertEquals($expected, $attributeSet->getSourceModel());
    }

    /**
     * @covers \App\Model\Attribute\AttributeSet::getAttributeColumnSettingsString
     * @covers \App\Model\Attribute\AttributeSet::getAttributeColumnSettings
     */
    public function testGetAttributeColumnSettingsString()
    {
        $attributeSet = new Attribute\AttributeSet($this->twig, $this->attribute, []);
        $this->assertContains("'unsigned' => true", $attributeSet->getAttributeColumnSettingsString());
    }
}
