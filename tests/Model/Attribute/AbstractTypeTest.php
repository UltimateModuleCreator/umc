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
namespace App\Tests\Model\Attribute;

use App\Model\Attribute;
use App\Model\Entity;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AbstractTypeTest extends TestCase
{
    /**
     * @var \Twig\Environment | MockObject
     */
    private $twig;

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->twig = $this->createMock(\Twig\Environment::class);
    }

    /**
     * @covers \App\Model\Attribute\AbstractType::__construct()
     * @covers \App\Model\Attribute\AbstractType::getAttribute()
     */
    public function testGetAttribute()
    {
        /** @var Attribute | MockObject $attribute */
        $attribute = $this->createMock(Attribute::class);
        $abstractType = new Attribute\AbstractType($this->twig, $attribute, []);
        $this->assertEquals($attribute, $abstractType->getAttribute());
    }

    /**
     * @covers \App\Model\Attribute\AbstractType::renderGrid()
     * @covers \App\Model\Attribute\AbstractType::render()
     */
    public function testRenderGrid()
    {
        /** @var Attribute | MockObject $attribute */
        $attribute = $this->createMock(Attribute::class);
        /** @var Entity | MockObject $attribute */
        $entity = $this->createMock(Entity::class);
        $attribute->method('getEntity')->willReturn($entity);
        $abstractType = new Attribute\AbstractType($this->twig, $attribute, ['grid_template' => 'tmpl.html.twig']);
        $this->twig->expects($this->once())->method('render')->willReturn('rendered');
        $this->assertEquals('rendered', $abstractType->renderGrid());
    }

    /**
     * @covers \App\Model\Attribute\AbstractType::renderForm()
     * @covers \App\Model\Attribute\AbstractType::render()
     */
    public function testRenderFormGrid()
    {
        /** @var Attribute | MockObject $attribute */
        $attribute = $this->createMock(Attribute::class);
        /** @var Entity | MockObject $attribute */
        $entity = $this->createMock(Entity::class);
        $attribute->method('getEntity')->willReturn($entity);
        $abstractType = new Attribute\AbstractType($this->twig, $attribute, ['form_template' => 'tmpl.html.twig']);
        $this->twig->expects($this->once())->method('render')->willReturn('rendered');
        $this->assertEquals('rendered', $abstractType->renderForm());
    }

    /**
     * @covers \App\Model\Attribute\AbstractType::renderFk()
     * @covers \App\Model\Attribute\AbstractType::render()
     */
    public function testRenderFk()
    {
        /** @var Attribute | MockObject $attribute */
        $attribute = $this->createMock(Attribute::class);
        /** @var Entity | MockObject $attribute */
        $entity = $this->createMock(Entity::class);
        $attribute->method('getEntity')->willReturn($entity);
        $abstractType = new Attribute\AbstractType($this->twig, $attribute, ['fk_template' => 'tmpl.html.twig']);
        $this->twig->expects($this->once())->method('render')->willReturn('rendered');
        $this->assertEquals('rendered', $abstractType->renderFk());
    }

    /**
     * @covers \App\Model\Attribute\AbstractType::renderSchemaFk()
     * @covers \App\Model\Attribute\AbstractType::render()
     */
    public function testRenderSchemaKf()
    {
        /** @var Attribute | MockObject $attribute */
        $attribute = $this->createMock(Attribute::class);
        /** @var Entity | MockObject $attribute */
        $entity = $this->createMock(Entity::class);
        $attribute->method('getEntity')->willReturn($entity);
        $abstractType = new Attribute\AbstractType($this->twig, $attribute, ['schema_fk_template' => 'tmpl.html.twig']);
        $this->twig->expects($this->once())->method('render')->willReturn('rendered');
        $this->assertEquals('rendered', $abstractType->renderSchemaFk());
    }

    /**
     * @covers \App\Model\Attribute\AbstractType::renderFrontendList()
     * @covers \App\Model\Attribute\AbstractType::render()
     */
    public function testRenderFrontendList()
    {
        /** @var Attribute | MockObject $attribute */
        $attribute = $this->createMock(Attribute::class);
        /** @var Entity | MockObject $attribute */
        $entity = $this->createMock(Entity::class);
        $attribute->method('getEntity')->willReturn($entity);
        $abstractType = new Attribute\AbstractType(
            $this->twig,
            $attribute,
            ['frontend_list_template' => 'tmpl.html.twig']
        );
        $this->twig->expects($this->once())->method('render')->willReturn('rendered');
        $this->assertEquals('rendered', $abstractType->renderFrontendList());
    }

    /**
     * @covers \App\Model\Attribute\AbstractType::renderFrontendView()
     * @covers \App\Model\Attribute\AbstractType::render()
     */
    public function testRenderFrontendView()
    {
        /** @var Attribute | MockObject $attribute */
        $attribute = $this->createMock(Attribute::class);
        /** @var Entity | MockObject $attribute */
        $entity = $this->createMock(Entity::class);
        $attribute->method('getEntity')->willReturn($entity);
        $abstractType = new Attribute\AbstractType(
            $this->twig,
            $attribute,
            ['frontend_view_template' => 'tmpl.html.twig']
        );
        $this->twig->expects($this->once())->method('render')->willReturn('rendered');
        $this->assertEquals('rendered', $abstractType->renderFrontendView());
    }

    /**
     * @covers \App\Model\Attribute\AbstractType::renderForm()
     * @covers \App\Model\Attribute\AbstractType::renderGrid()
     * @covers \App\Model\Attribute\AbstractType::render()
     */
    public function testRenderWrongType()
    {
        /** @var Attribute | MockObject $attribute */
        $attribute = $this->createMock(Attribute::class);
        /** @var Entity | MockObject $attribute */
        $entity = $this->createMock(Entity::class);
        $attribute->method('getEntity')->willReturn($entity);
        $abstractType = new Attribute\AbstractType($this->twig, $attribute, []);
        $this->twig->expects($this->never())->method('render');
        $this->assertEquals('', $abstractType->renderForm());
        $this->assertEquals('', $abstractType->renderGrid());
    }

    /**
     * @covers \App\Model\Attribute\AbstractType::getAttributeColumnSettings
     * @covers \App\Model\Attribute\AbstractType::getAttributeColumnSettingsString
     */
    public function testGetAttributeColumnSettingsString()
    {
        /** @var Attribute | MockObject $attribute */
        $attribute = $this->createMock(Attribute::class);
        $attribute->method('getRequired')->willReturn(true);
        $expected = "[" . PHP_EOL . "    'nullable' => false," . PHP_EOL . ']';
        $abstractType = new Attribute\AbstractType($this->twig, $attribute, []);
        $this->assertEquals($expected, $abstractType->getAttributeColumnSettingsString(0));
    }

    /**
     * @covers \App\Model\Attribute\AbstractType::getAttributeColumnSettings
     * @covers \App\Model\Attribute\AbstractType::getAttributeColumnSettingsString
     */
    public function testGetAttributeColumnSettingsStringNotRequired()
    {
        /** @var Attribute | MockObject $attribute */
        $attribute = $this->createMock(Attribute::class);
        $attribute->method('getRequired')->willReturn(false);
        $expected = "[" . PHP_EOL . "]";
        $abstractType = new Attribute\AbstractType($this->twig, $attribute, []);
        $this->assertEquals($expected, $abstractType->getAttributeColumnSettingsString(0));
    }

    /**
     * @covers \App\Model\Attribute\AbstractType::getGridFilterType
     * @covers \App\Model\Attribute\AbstractType::__construct
     */
    public function testGetGridFilterType()
    {
        /** @var Attribute | MockObject $attribute */
        $attribute = $this->createMock(Attribute::class);
        $type = new Attribute\AbstractType($this->twig, $attribute, ['grid_filter_type' => 'select']);
        $this->assertEquals('select', $type->getGridFilterType());
    }

    /**
     * @covers \App\Model\Attribute\AbstractType::getMultiple
     * @covers \App\Model\Attribute\AbstractType::__construct
     */
    public function testGetMultiple()
    {
        /** @var Attribute | MockObject $attribute */
        $attribute = $this->createMock(Attribute::class);
        $type = new Attribute\AbstractType($this->twig, $attribute, ['multiple' => 1]);
        $this->assertTrue($type->getMultiple());

        $type = new Attribute\AbstractType($this->twig, $attribute, ['multiple' => 0]);
        $this->assertFalse($type->getMultiple());
    }

    /**
     * @covers \App\Model\Attribute\AbstractType::getMultipleText
     * @covers \App\Model\Attribute\AbstractType::__construct
     */
    public function testGetMultipleText()
    {
        /** @var Attribute | MockObject $attribute */
        $attribute = $this->createMock(Attribute::class);
        $type = new Attribute\AbstractType($this->twig, $attribute, ['multiple_text' => 'multiple']);
        $this->assertEquals('multiple', $type->getMultipleText());
    }

    /**
     * @covers \App\Model\Attribute\AbstractType::getUploadType
     * @covers \App\Model\Attribute\AbstractType::__construct
     */
    public function testGetUploadType()
    {
        /** @var Attribute | MockObject $attribute */
        $attribute = $this->createMock(Attribute::class);
        $type = new Attribute\AbstractType($this->twig, $attribute, ['upload_type' => 'image']);
        $this->assertEquals('image', $type->getUploadType());
    }

    /**
     * @covers \App\Model\Attribute\AbstractType::getSqlTypeConstant
     * @covers \App\Model\Attribute\AbstractType::__construct
     */
    public function testGetSqlTypeConstant()
    {
        /** @var Attribute | MockObject $attribute */
        $attribute = $this->createMock(Attribute::class);
        $type = new Attribute\AbstractType($this->twig, $attribute, ['sql_type_constant' => 'CONSTANT']);
        $this->assertEquals('CONSTANT', $type->getSqlTypeConstant());
    }

    /**
     * @covers \App\Model\Attribute\AbstractType::getSqlSize
     * @covers \App\Model\Attribute\AbstractType::__construct
     */
    public function testGetSqlSize()
    {
        /** @var Attribute | MockObject $attribute */
        $attribute = $this->createMock(Attribute::class);
        $type = new Attribute\AbstractType($this->twig, $attribute, ['sql_size' => '64k']);
        $this->assertEquals('64k', $type->getSqlSize());
    }

    /**
     * @covers \App\Model\Attribute\AbstractType::getCanHaveOptions
     * @covers \App\Model\Attribute\AbstractType::__construct
     */
    public function testGetCanHaveOptions()
    {
        /** @var Attribute | MockObject $attribute */
        $attribute = $this->createMock(Attribute::class);
        $type = new Attribute\AbstractType($this->twig, $attribute, ['can_have_options' => 1]);
        $this->assertTrue($type->getCanHaveOptions());

        $type = new Attribute\AbstractType($this->twig, $attribute, []);
        $this->assertFalse($type->getCanHaveOptions());
    }

    /**
     * @covers \App\Model\Attribute\AbstractType::getTypeHint
     * @covers \App\Model\Attribute\AbstractType::__construct
     */
    public function testGetTypeHint()
    {
        /** @var Attribute | MockObject $attribute */
        $attribute = $this->createMock(Attribute::class);
        $type = new Attribute\AbstractType($this->twig, $attribute, ['type_hint' => 'string']);
        $this->assertEquals('string', $type->getTypeHint());
    }

    /**
     * @covers \App\Model\Attribute\AbstractType::getAttributeColumnSettingsStringXml
     * @covers \App\Model\Attribute\AbstractType::__construct()
     */
    public function testGetAttributeColumnSettingsStringXmlWithRequired()
    {
        /** @var Attribute | MockObject $attribute */
        $attribute = $this->createMock(Attribute::class);
        $attribute->method('getRequired')->willReturn(true);
        $type = new Attribute\AbstractType($this->twig, $attribute, ['schema_attributes' => 'a="b"']);
        $this->assertEquals('a="b" nullable="false"', $type->getAttributeColumnSettingsStringXml());
        $type = new Attribute\AbstractType($this->twig, $attribute, []);
        $this->assertEquals('nullable="false"', $type->getAttributeColumnSettingsStringXml());
    }

    /**
     * @covers \App\Model\Attribute\AbstractType::getAttributeColumnSettingsStringXml
     * @covers \App\Model\Attribute\AbstractType::__construct()
     */
    public function testGetAttributeColumnSettingsStringXmlWithoutRequired()
    {
        /** @var Attribute | MockObject $attribute */
        $attribute = $this->createMock(Attribute::class);
        $attribute->method('getRequired')->willReturn(false);
        $type = new Attribute\AbstractType($this->twig, $attribute, ['schema_attributes' => 'a="b"']);
        $this->assertEquals('a="b" nullable="true"', $type->getAttributeColumnSettingsStringXml());
        $type = new Attribute\AbstractType($this->twig, $attribute, []);
        $this->assertEquals('nullable="true"', $type->getAttributeColumnSettingsStringXml());
    }

    /**
     * @covers \App\Model\Attribute\AbstractType::getSchemaType
     * @covers \App\Model\Attribute\AbstractType::__construct
     */
    public function testGetSchemaType()
    {
        /** @var Attribute | MockObject $attribute */
        $attribute = $this->createMock(Attribute::class);
        $type = new Attribute\AbstractType($this->twig, $attribute, ['schema_type' => 'schema']);
        $this->assertEquals('schema', $type->getSchemaType());
    }

    /**
     * @covers \App\Model\Attribute\AbstractType::getFullText
     * @covers \App\Model\Attribute\AbstractType::__construct
     */
    public function testGetFullText()
    {
        /** @var Attribute | MockObject $attribute */
        $attribute = $this->createMock(Attribute::class);
        $type = new Attribute\AbstractType($this->twig, $attribute, ['full_text' => 1]);
        $this->assertTrue($type->getFullText());
        $type = new Attribute\AbstractType($this->twig, $attribute, ['full_text' => 0]);
        $this->assertFalse($type->getFullText());
        $type = new Attribute\AbstractType($this->twig, $attribute, []);
        $this->assertFalse($type->getFullText());
    }
}
