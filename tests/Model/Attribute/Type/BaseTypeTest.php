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

namespace App\Test\Model\Attribute\Type;

use App\Model\Attribute;
use App\Model\Attribute\Type\BaseType;
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
     * @var Attribute | MockObject
     */
    private $attribute;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->twig = $this->createMock(Environment::class);
        $this->attribute = $this->createMock(Attribute::class);
        $this->twig->method('render')->willReturn('text');
    }

    /**
     * @covers \App\Model\Attribute\Type\BaseType::getAttribute
     * @covers \App\Model\Attribute\Type\BaseType::__construct
     */
    public function testGetAttribute()
    {
        $this->assertEquals($this->attribute, $this->getInstance([])->getAttribute());
    }

    /**
     * @covers \App\Model\Attribute\Type\BaseType::getLabel
     * @covers \App\Model\Attribute\Type\BaseType::__construct
     */
    public function testGetLabel()
    {
        $this->assertEquals('label', $this->getInstance(['label' => 'label'])->getLabel());
        $this->assertEquals('', $this->getInstance([])->getLabel());
    }

    /**
     * @covers \App\Model\Attribute\Type\BaseType::getGridFilterType
     * @covers \App\Model\Attribute\Type\BaseType::__construct
     */
    public function testGetGridFilterType()
    {
        $this->assertEquals('filter', $this->getInstance(['grid_filter_type' => 'filter'])->getGridFilterType());
        $this->assertEquals('', $this->getInstance([])->getGridFilterType());
    }

    /**
     * @covers \App\Model\Attribute\Type\BaseType::isCanBeName
     * @covers \App\Model\Attribute\Type\BaseType::__construct
     */
    public function testIsCanBeName()
    {
        $this->assertTrue($this->getInstance(['can_be_name' => 1])->isCanBeName());
        $this->assertFalse($this->getInstance([])->isCanBeName());
    }

    /**
     * @covers \App\Model\Attribute\Type\BaseType::isCanShowInGrid
     * @covers \App\Model\Attribute\Type\BaseType::__construct
     */
    public function testIsCanShowInGrid()
    {
        $this->assertTrue($this->getInstance(['can_show_in_grid' => 1])->isCanShowInGrid());
        $this->assertFalse($this->getInstance([])->isCanShowInGrid());
    }

    /**
     * @covers \App\Model\Attribute\Type\BaseType::isCanHaveOptions
     * @covers \App\Model\Attribute\Type\BaseType::__construct
     */
    public function testIsCanHaveOptions()
    {
        $this->assertTrue($this->getInstance(['can_have_options' => 1])->isCanHaveOptions());
        $this->assertFalse($this->getInstance([])->isCanHaveOptions());
    }

    /**
     * @covers \App\Model\Attribute\Type\BaseType::isCanBeRequired
     * @covers \App\Model\Attribute\Type\BaseType::__construct
     */
    public function testIsCanBeRequired()
    {
        $this->assertTrue($this->getInstance(['can_be_required' => 1])->isCanBeRequired());
        $this->assertFalse($this->getInstance([])->isCanBeRequired());
    }

    /**
     * @covers \App\Model\Attribute\Type\BaseType::isFullText
     * @covers \App\Model\Attribute\Type\BaseType::__construct
     */
    public function testIsFullText()
    {
        $this->assertTrue($this->getInstance(['full_text' => 1])->isFullText());
        $this->assertFalse($this->getInstance([])->isFullText());
    }

    /**
     * @covers \App\Model\Attribute\Type\BaseType::getTypeHint
     * @covers \App\Model\Attribute\Type\BaseType::__construct
     */
    public function testGetTypeHint()
    {
        $this->assertEquals('hint', $this->getInstance(['type_hint' => 'hint'])->getTypeHint());
        $this->assertEquals('string', $this->getInstance([])->getTypeHint());
    }

    /**
     * @covers \App\Model\Attribute\Type\BaseType::getSchemaType
     * @covers \App\Model\Attribute\Type\BaseType::__construct
     */
    public function testGetSchemaType()
    {
        $this->assertEquals('schema_type', $this->getInstance(['schema_type' => 'schema_type'])->getSchemaType());
        $this->assertEquals('varchar', $this->getInstance([])->getSchemaType());
    }

    /**
     * @covers \App\Model\Attribute\Type\BaseType::getSchemaAttributes
     * @covers \App\Model\Attribute\Type\BaseType::__construct
     */
    public function testGetSchemaAttributes()
    {
        $this->assertEquals(
            'attributes',
            $this->getInstance(['schema_attributes' => 'attributes'])->getSchemaAttributes()
        );
        $this->assertEquals('', $this->getInstance([])->getSchemaAttributes());
    }

    /**
     * @covers \App\Model\Attribute\Type\BaseType::isCanFilterInGrid
     * @covers \App\Model\Attribute\Type\BaseType::__construct
     */
    public function testIsCanFilterInGrid()
    {
        $this->assertTrue($this->getInstance(['can_filter_in_grid' => 1])->isCanFilterInGrid());
        $this->assertFalse($this->getInstance([])->isCanFilterInGrid());
    }

    /**
     * @covers \App\Model\Attribute\Type\BaseType::isUpload
     * @covers \App\Model\Attribute\Type\BaseType::__construct
     */
    public function testIsUpload()
    {
        $this->assertTrue($this->getInstance(['upload' => 1])->isUpload());
        $this->assertFalse($this->getInstance([])->isUpload());
    }

    /**
     * @covers \App\Model\Attribute\Type\BaseType::getProcessorType
     * @covers \App\Model\Attribute\Type\BaseType::__construct
     */
    public function testGetProcessorType()
    {
        $this->assertEquals(
            'type',
            $this->getInstance(['processor' => ['type' => 'type']])->getProcessorType('type')
        );
        $this->assertEquals('', $this->getInstance([])->getProcessorType('type'));
    }

    /**
     * @covers \App\Model\Attribute\Type\BaseType::isMultiple
     * @covers \App\Model\Attribute\Type\BaseType::__construct
     */
    public function testIsMultiple()
    {
        $this->assertTrue($this->getInstance(['multiple' => 1])->isMultiple());
        $this->assertFalse($this->getInstance([])->isMultiple());
    }

    /**
     * @covers \App\Model\Attribute\Type\BaseType::isProductAttribute
     * @covers \App\Model\Attribute\Type\BaseType::__construct
     */
    public function testIsProductAttribute()
    {
        $this->assertFalse($this->getInstance([])->isProductAttribute());
    }

    /**
     * @covers \App\Model\Attribute\Type\BaseType::isProductAttributeSet
     * @covers \App\Model\Attribute\Type\BaseType::__construct
     */
    public function testIsProductAttributeSet()
    {
        $this->assertFalse($this->getInstance([])->isProductAttributeSet());
    }

    /**
     * @covers \App\Model\Attribute\Type\BaseType::getDefaultValue
     * @covers \App\Model\Attribute\Type\BaseType::__construct
     */
    public function testGetDefaultValue()
    {
        $this->attribute->expects($this->once())->method('getRawDefaultValue')->willReturn("a\nb \n c  ");
        $this->assertEquals('a,b,c', $this->getInstance([])->getDefaultValue());
    }

    /**
     * @covers \App\Model\Attribute\Type\BaseType::getMultipleText
     * @covers \App\Model\Attribute\Type\BaseType::__construct
     */
    public function testGetMultipleText()
    {
        $this->assertEquals('true', $this->getInstance(['multiple' => 1])->getMultipleText());
        $this->assertEquals('false', $this->getInstance([])->getMultipleText());
    }

    /**
     * @covers \App\Model\Attribute\Type\BaseType::getAttributeColumnSettingsStringXml
     * @covers \App\Model\Attribute\Type\BaseType::__construct
     */
    public function testGetAttributeColumnSettingsStringXml()
    {
        $this->attribute->expects($this->once())->method('isRequired')->willReturn(true);
        $expected = 'attributes nullable="false"';
        $this->assertEquals(
            $expected,
            $this->getInstance(['schema_attributes' => 'attributes'])->getAttributeColumnSettingsStringXml()
        );
    }

    /**
     * @covers \App\Model\Attribute\Type\BaseType::getSourceModel
     * @covers \App\Model\Attribute\Type\BaseType::__construct
     */
    public function testGetSourceModel()
    {
        $this->attribute->method('getOptionSourceVirtualType')->willReturn('AttributeSourceModel');
        $this->assertEquals('source_model', $this->getInstance(['source_model' => 'source_model'])->getSourceModel());
        $this->assertEquals('AttributeSourceModel', $this->getInstance([])->getSourceModel());
    }

    /**
     * @covers \App\Model\Attribute\Type\BaseType::getIndexDeleteType
     * @covers \App\Model\Attribute\Type\BaseType::__construct
     */
    public function testGetIndexDeleteTypeAttributeRequired()
    {
        $this->attribute->expects($this->once())->method('isRequired')->willReturn(true);
        $this->assertEquals('CASCADE', $this->getInstance([])->getIndexDeleteType());
    }

    /**
     * @covers \App\Model\Attribute\Type\BaseType::getIndexDeleteType
     * @covers \App\Model\Attribute\Type\BaseType::__construct
     */
    public function testGetIndexDeleteTypeAttributeNotRequired()
    {
        $this->attribute->expects($this->once())->method('isRequired')->willReturn(false);
        $this->assertEquals('SET NULL', $this->getInstance([])->getIndexDeleteType());
    }

    /**
     * @covers \App\Model\Attribute\Type\BaseType::renderGrid
     * @covers \App\Model\Attribute\Type\BaseType::renderTemplate
     * @covers \App\Model\Attribute\Type\BaseType::__construct
     */
    public function testRenderGridNoTemplate()
    {
        $type = $this->getInstance([]);
        $this->attribute->expects($this->never())->method('getEntity');
        $this->assertEquals('', $type->renderGrid());
    }

    /**
     * @covers \App\Model\Attribute\Type\BaseType::renderGrid
     * @covers \App\Model\Attribute\Type\BaseType::renderTemplate
     * @covers \App\Model\Attribute\Type\BaseType::__construct
     */
    public function testRenderGridWithTemplate()
    {
        $type = $this->getInstance(['templates' => ['backend' => ['grid' => 'template']]]);
        $entity = $this->createMock(Entity::class);
        $module = $this->createMock(Module::class);
        $this->attribute->expects($this->once())->method('getEntity')->willReturn($entity);
        $entity->expects($this->once())->method('getModule')->willReturn($module);
        $this->assertEquals('text', $type->renderGrid());
    }

    /**
     * @covers \App\Model\Attribute\Type\BaseType::renderSchemaFk
     * @covers \App\Model\Attribute\Type\BaseType::renderTemplate
     * @covers \App\Model\Attribute\Type\BaseType::__construct
     */
    public function testRenderSchemaFkNoTemplate()
    {
        $type = $this->getInstance([]);
        $this->attribute->expects($this->never())->method('getEntity');
        $this->assertEquals('', $type->renderSchemaFk());
    }

    /**
     * @covers \App\Model\Attribute\Type\BaseType::renderForm
     * @covers \App\Model\Attribute\Type\BaseType::renderTemplate
     * @covers \App\Model\Attribute\Type\BaseType::__construct
     */
    public function testRenderSchemaFkWithTemplate()
    {
        $type = $this->getInstance(['templates' => ['schema_fk' => 'template']]);
        $entity = $this->createMock(Entity::class);
        $module = $this->createMock(Module::class);
        $this->attribute->expects($this->once())->method('getEntity')->willReturn($entity);
        $entity->expects($this->once())->method('getModule')->willReturn($module);
        $this->assertEquals('text', $type->renderSchemaFk());
    }

    /**
     * @covers \App\Model\Attribute\Type\BaseType::renderGrid
     * @covers \App\Model\Attribute\Type\BaseType::renderTemplate
     * @covers \App\Model\Attribute\Type\BaseType::__construct
     */
    public function testRenderFormNoTemplate()
    {
        $type = $this->getInstance([]);
        $this->attribute->expects($this->never())->method('getEntity');
        $this->assertEquals('', $type->renderForm());
    }

    /**
     * @covers \App\Model\Attribute\Type\BaseType::renderForm
     * @covers \App\Model\Attribute\Type\BaseType::renderTemplate
     * @covers \App\Model\Attribute\Type\BaseType::__construct
     */
    public function testRenderFormWithTemplate()
    {
        $type = $this->getInstance(['templates' => ['backend' => ['form' => 'template']]]);
        $entity = $this->createMock(Entity::class);
        $module = $this->createMock(Module::class);
        $this->attribute->expects($this->once())->method('getEntity')->willReturn($entity);
        $entity->expects($this->once())->method('getModule')->willReturn($module);
        $this->assertEquals('text', $type->renderForm());
    }

    /**
     * @param $data
     * @return BaseType
     */
    private function getInstance($data): BaseType
    {
        return new BaseType($this->twig, $this->attribute, $data);
    }
}
