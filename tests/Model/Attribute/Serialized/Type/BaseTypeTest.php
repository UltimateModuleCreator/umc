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

namespace App\Model\Test\Unit\Attribute\Serialized\Type;

use App\Model\Attribute;
use App\Model\Attribute\Serialized;
use App\Model\Attribute\Serialized\Type\BaseType;
use App\Model\Entity;
use App\Model\Module;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

class BaseTypeTest extends TestCase
{
    /**
     * @var Environment | MockObject
     */
    private $twig;
    /**
     * @var Serialized | MockObject
     */
    private $serialized;

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->twig = $this->createMock(Environment::class);
        $this->serialized = $this->createMock(Serialized::class);
    }

    /**
     * @covers \App\Model\Attribute\Serialized\Type\BaseType::getMultipleText
     * @covers \App\Model\Attribute\Serialized\Type\BaseType::__construct
     */
    public function testGetMultipleText()
    {
        $this->assertEquals('false', $this->getInstance([])->getMultipleText());
        $this->assertEquals('true', $this->getInstance(['multiple' => true])->getMultipleText());
        $this->assertEquals('false', $this->getInstance(['multiple' => 0])->getMultipleText());
    }

    /**
     * @covers \App\Model\Attribute\Serialized\Type\BaseType::isCanHaveOptions
     * @covers \App\Model\Attribute\Serialized\Type\BaseType::__construct
     */
    public function testIsCanHaveOptions()
    {
        $this->assertFalse($this->getInstance([])->isCanHaveOptions());
        $this->assertTrue($this->getInstance(['can_have_options' => true])->isCanHaveOptions());
        $this->assertFalse($this->getInstance(['can_have_options' => 0])->isCanHaveOptions());
    }

    /**
     * @covers \App\Model\Attribute\Serialized\Type\BaseType::getSourceModel
     * @covers \App\Model\Attribute\Serialized\Type\BaseType::__construct
     */
    public function testGetSourceModelWithPresetValue()
    {
        $this->serialized->expects($this->never())->method('getOptionSourceVirtualType');
        $this->assertEquals('source_model', $this->getInstance(['source_model' => 'source_model'])->getSourceModel());
    }

    /**
     * @covers \App\Model\Attribute\Serialized\Type\BaseType::getSourceModel
     * @covers \App\Model\Attribute\Serialized\Type\BaseType::__construct
     */
    public function testGetSourceModelWithDefaultValue()
    {
        $this->serialized->expects($this->once())->method('getOptionSourceVirtualType')->willReturn('default');
        $this->assertEquals('default', $this->getInstance([])->getSourceModel());
    }

    /**
     * @covers \App\Model\Attribute\Serialized\Type\BaseType::getSerialized
     * @covers \App\Model\Attribute\Serialized\Type\BaseType::__construct
     */
    public function testGetSerialized()
    {
        $this->assertEquals($this->serialized, $this->getInstance([])->getSerialized());
    }

    /**
     * @covers \App\Model\Attribute\Serialized\Type\BaseType::isProductAttributeSet
     * @covers \App\Model\Attribute\Serialized\Type\BaseType::__construct
     */
    public function testIsProductAttributeSet()
    {
        $this->assertFalse($this->getInstance([])->isProductAttributeSet());
    }

    /**
     * @covers \App\Model\Attribute\Serialized\Type\BaseType::isProductAttribute
     * @covers \App\Model\Attribute\Serialized\Type\BaseType::__construct
     */
    public function testIsProductAttribute()
    {
        $this->assertFalse($this->getInstance([])->isProductAttribute());
    }

    /**
     * @covers \App\Model\Attribute\Serialized\Type\BaseType::renderForm
     * @covers \App\Model\Attribute\Serialized\Type\BaseType::renderTemplate
     * @covers \App\Model\Attribute\Serialized\Type\BaseType::__construct
     */
    public function testRenderFormNoTemplate()
    {
        $type = $this->getInstance([]);
        $this->serialized->expects($this->never())->method('getAttribute');
        $this->assertEquals('', $type->renderForm());
    }

    /**
     * @covers \App\Model\Attribute\Serialized\Type\BaseType::renderForm
     * @covers \App\Model\Attribute\Serialized\Type\BaseType::renderTemplate
     * @covers \App\Model\Attribute\Serialized\Type\BaseType::__construct
     */
    public function testRenderFormWithTemplate()
    {
        $type = $this->getInstance(['templates' => ['serialized' => ['backend' => 'template']]]);
        $attribute = $this->createMock(Attribute::class);
        $entity = $this->createMock(Entity::class);
        $module = $this->createMock(Module::class);
        $this->serialized->expects($this->once())->method('getAttribute')->willReturn($attribute);
        $attribute->expects($this->once())->method('getEntity')->willReturn($entity);
        $entity->expects($this->once())->method('getModule')->willReturn($module);
        $this->twig->expects($this->once())->method('render')->willReturn('rendered');
        $this->assertEquals('rendered', $type->renderForm());
    }

    /**
     * @param array $data
     * @return BaseType
     */
    private function getInstance(array $data): BaseType
    {
        return new BaseType($this->twig, $this->serialized, $data);
    }
}
