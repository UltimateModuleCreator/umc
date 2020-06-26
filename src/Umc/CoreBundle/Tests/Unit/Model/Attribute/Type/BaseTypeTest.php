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

namespace App\Umc\CoreBundle\Tests\Unit\Model\Attribute\Type;

use App\Umc\CoreBundle\Model\Attribute;
use App\Umc\CoreBundle\Model\Attribute\Type\BaseType;
use App\Umc\CoreBundle\Model\Entity;
use App\Umc\CoreBundle\Model\Module;
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
     * @covers \App\Umc\CoreBundle\Model\Attribute\Type\BaseType::getAttribute
     * @covers \App\Umc\CoreBundle\Model\Attribute\Type\BaseType::__construct
     */
    public function testGetAttribute()
    {
        $this->assertEquals($this->attribute, $this->getInstance([])->getAttribute());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Type\BaseType::getLabel
     * @covers \App\Umc\CoreBundle\Model\Attribute\Type\BaseType::__construct
     */
    public function testGetLabel()
    {
        $this->assertEquals('label', $this->getInstance(['label' => 'label'])->getLabel());
        $this->assertEquals('', $this->getInstance([])->getLabel());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Type\BaseType::getGridFilterType
     * @covers \App\Umc\CoreBundle\Model\Attribute\Type\BaseType::__construct
     */
    public function testGetGridFilterType()
    {
        $this->assertEquals('filter', $this->getInstance(['grid_filter_type' => 'filter'])->getGridFilterType());
        $this->assertEquals('', $this->getInstance([])->getGridFilterType());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Type\BaseType::getTypeHint
     * @covers \App\Umc\CoreBundle\Model\Attribute\Type\BaseType::__construct
     */
    public function testGetTypeHint()
    {
        $this->assertEquals('hint', $this->getInstance(['type_hint' => 'hint'])->getTypeHint());
        $this->assertEquals('string', $this->getInstance([])->getTypeHint());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Type\BaseType::getSchemaType
     * @covers \App\Umc\CoreBundle\Model\Attribute\Type\BaseType::__construct
     */
    public function testGetSchemaType()
    {
        $this->assertEquals('schema_type', $this->getInstance(['schema_type' => 'schema_type'])->getSchemaType());
        $this->assertEquals('varchar', $this->getInstance([])->getSchemaType());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Type\BaseType::getSchemaAttributes
     * @covers \App\Umc\CoreBundle\Model\Attribute\Type\BaseType::__construct
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
     * @covers \App\Umc\CoreBundle\Model\Attribute\Type\BaseType::isCanFilterInGrid
     * @covers \App\Umc\CoreBundle\Model\Attribute\Type\BaseType::__construct
     */
    public function testIsCanFilterInGrid()
    {
        $this->assertTrue($this->getInstance(['can_filter_in_grid' => 1])->isCanFilterInGrid());
        $this->assertFalse($this->getInstance([])->isCanFilterInGrid());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Type\BaseType::getFlags
     * @covers \App\Umc\CoreBundle\Model\Attribute\Type\BaseType::__construct
     */
    public function testGetFlags()
    {
        $instance = $this->getInstance([
            'flags' => [
                'flag1' => true,
                'flag2' => false
            ]
        ]);
        $this->assertEquals(['flag1'], $instance->getFlags());
        $this->assertEquals([], $this->getInstance([])->getFlags());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Type\BaseType::hasFlag
     * @covers \App\Umc\CoreBundle\Model\Attribute\Type\BaseType::__construct
     */
    public function testHasFlag()
    {
        $instance = $this->getInstance([
            'flags' => [
                'flag1' => true,
                'flag2' => false
            ]
        ]);
        $this->assertTrue($instance->hasFlag('flag1'));
        $this->assertFalse($instance->hasFlag('flag2'));
        $this->assertFalse($instance->hasFlag('missing'));
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Type\BaseType::getDefaultValue
     * @covers \App\Umc\CoreBundle\Model\Attribute\Type\BaseType::__construct
     */
    public function testGetDefaultValue()
    {
        $this->attribute->expects($this->once())->method('getRawDefaultValue')->willReturn("a\nb \n c  ");
        $this->assertEquals('a,b,c', $this->getInstance([])->getDefaultValue());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Type\BaseType::renderTemplate
     * @covers \App\Umc\CoreBundle\Model\Attribute\Type\BaseType::__construct
     */
    public function testRenderTemplate()
    {
        $type = $this->getInstance(['templates' => ['template' => 'template_name']]);
        $this->twig->method('render')->willReturn('text');
        $entity = $this->createMock(Entity::class);
        $module = $this->createMock(Module::class);
        $this->attribute->expects($this->once())->method('getEntity')->willReturn($entity);
        $entity->expects($this->once())->method('getModule')->willReturn($module);
        $this->assertEquals('text', $type->renderTemplate('template'));
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Type\BaseType::renderTemplate
     * @covers \App\Umc\CoreBundle\Model\Attribute\Type\BaseType::__construct
     */
    public function testRenderTemplateNoTemplate()
    {
        $type = $this->getInstance(['templates' => ['template' => 'template_name']]);
        $this->assertEquals('', $type->renderTemplate('missing'));
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
