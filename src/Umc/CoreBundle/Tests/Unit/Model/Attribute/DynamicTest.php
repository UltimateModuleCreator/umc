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

namespace App\Umc\CoreBundle\Tests\Unit\Model\Attribute;

use App\Umc\CoreBundle\Model\Attribute;
use App\Umc\CoreBundle\Model\Attribute\Dynamic;
use App\Umc\CoreBundle\Model\Attribute\Dynamic\Option\Factory as OptionFactory;
use App\Umc\CoreBundle\Model\Attribute\Dynamic\Type\Factory as TypeFactory;
use App\Umc\CoreBundle\Model\Entity;
use App\Umc\CoreBundle\Model\Module;
use App\Umc\CoreBundle\Util\StringUtil;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DynamicTest extends TestCase
{
    /**
     * @var OptionFactory | MockObject
     */
    private $optionFactory;
    /**
     * @var TypeFactory | MockObject
     */
    private $typeFactory;
    /**
     * @var StringUtil | MockObject
     */
    private $stringUtil;
    /**
     * @var Attribute | MockObject
     */
    private $attribute;
    /**
     * @var Dynamic
     */
    private $dynamic;
    /**
     * @var Dynamic\Type\BaseType | MockObject
     */
    private $type;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->optionFactory = $this->createMock(OptionFactory::class);
        $this->typeFactory = $this->createMock(TypeFactory::class);
        $this->stringUtil = $this->createMock(StringUtil::class);
        $this->attribute = $this->createMock(Attribute::class);
        $this->type = $this->createMock(Dynamic\Type\BaseType::class);
        $this->typeFactory->method('create')->willReturn($this->type);
        $this->dynamic = new Dynamic(
            $this->optionFactory,
            $this->typeFactory,
            $this->stringUtil,
            $this->attribute,
            []
        );
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic::getCode
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic::__construct
     */
    public function testGetCode()
    {
        $this->assertEquals('code', $this->getInstance(['code' => 'code'])->getCode());
        $this->assertEquals('', $this->getInstance([])->getCode());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic::getLabel
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic::__construct
     */
    public function testGetLabel()
    {
        $this->assertEquals('label', $this->getInstance(['label' => 'label'])->getLabel());
        $this->assertEquals('', $this->getInstance([])->getLabel());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic::getType
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic::__construct
     */
    public function testGetType()
    {
        $this->assertEquals('type', $this->getInstance(['type' => 'type'])->getType());
        $this->assertEquals('', $this->getInstance([])->getType());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic::isExpanded
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic::__construct
     */
    public function testIsExpanded()
    {
        $this->assertTrue($this->getInstance(['expanded' => '2'])->isExpanded());
        $this->assertFalse($this->getInstance([])->isExpanded());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic::isRequired
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic::__construct
     */
    public function testIsRequired()
    {
        $this->assertTrue($this->getInstance(['required' => true])->isRequired());
        $this->assertFalse($this->getInstance([])->isRequired());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic::isShowInList
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic::__construct
     */
    public function testIsShowInList()
    {
        $this->assertTrue($this->getInstance(['show_in_list' => true])->isShowInList());
        $this->assertFalse($this->getInstance([])->isShowInList());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic::isShowInView
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic::__construct
     */
    public function testIsShowInView()
    {
        $this->assertTrue($this->getInstance(['show_in_view' => true])->isShowInView());
        $this->assertFalse($this->getInstance([])->isShowInView());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic::getNote
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic::__construct
     */
    public function testGetNote()
    {
        $this->assertEquals('note', $this->getInstance(['note' => 'note'])->getNote());
        $this->assertEquals('', $this->getInstance([])->getNote());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic::getTooltip
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic::__construct
     */
    public function testGetTooltip()
    {
        $this->assertEquals('tooltip', $this->getInstance(['tooltip' => 'tooltip'])->getTooltip());
        $this->assertEquals('', $this->getInstance([])->getTooltip());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic::getDefaultValue
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic::__construct
     */
    public function testGetDefaultValue()
    {
        $this->assertEquals('default', $this->getInstance(['default_value' => 'default'])->getDefaultValue());
        $this->assertEquals('', $this->getInstance([])->getDefaultValue());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic::getAttribute
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic::__construct
     */
    public function testGetAttribute()
    {
        $this->assertEquals($this->attribute, $this->getInstance([])->getAttribute());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic::isManualOptions
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic::__construct
     */
    public function testIsManualOptions()
    {
        $this->type->expects($this->once())->method('hasFlag')->with('manual_options')->willReturn(true);
        $this->assertTrue($this->getInstance([])->isManualOptions());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic::getOptions
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic::__construct
     */
    public function testGetOptions()
    {
        $this->type->expects($this->once())->method('hasFlag')->with('manual_options')->willReturn(true);
        $this->optionFactory->expects($this->exactly(2))->method('create')->willReturn(
            $this->createMock(Dynamic\Option::class)
        );
        $this->assertEquals(
            2,
            count($this->getInstance(['_options' => [['option1'], ['option2']]])->getOptions())
        );
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic::getOptions
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic::__construct
     */
    public function testGetOptionsNotAllowed()
    {
        $this->type->expects($this->once())->method('hasFlag')->with('manual_options')->willReturn(false);
        $this->optionFactory->expects($this->exactly(2))->method('create')->willReturn(
            $this->createMock(Dynamic\Option::class)
        );
        $options = [
            ['option1'],
            ['option2']
        ];
        $this->assertEquals(
            [],
            $this->getInstance(['_options' => $options])->getOptions()
        );
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic::getOptionType
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic::__construct
     */
    public function testGetOptionType()
    {
        $this->type->expects($this->once())->method('hasFlag')->with('manual_options')->willReturn(true);
        $option1 = $this->createMock(Dynamic\Option::class);
        $option1->method('getValue')->willReturn(1);
        $option2 = $this->createMock(Dynamic\Option::class);
        $option2->method('getValue')->willReturn('option2');
        $options = [
            ['option1'],
            ['option2']
        ];
        $this->optionFactory->expects($this->exactly(2))->method('create')
            ->willReturnOnConsecutiveCalls($option1, $option2);
        $this->assertEquals(
            'string',
            $this->getInstance(['_options' => $options])->getOptionType()
        );
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic::getOptionType
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic::__construct
     */
    public function testGetOptionTypeAllNumbers()
    {
        $this->type->expects($this->once())->method('hasFlag')->with('manual_options')->willReturn(true);
        $option1 = $this->createMock(Dynamic\Option::class);
        $option1->method('getValue')->willReturn(1);
        $option2 = $this->createMock(Dynamic\Option::class);
        $option2->method('getValue')->willReturn(2);
        $options = [
            ['option1'],
            ['option2']
        ];
        $this->optionFactory->expects($this->exactly(2))->method('create')
            ->willReturnOnConsecutiveCalls($option1, $option2);
        $this->assertEquals(
            'number',
            $this->getInstance(['_options' => $options])->getOptionType()
        );
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic::toArray
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic::__construct
     */
    public function testToArray()
    {
        $option = $this->createMock(Dynamic\Option::class);
        $option->expects($this->once())->method('toArray');
        $this->optionFactory->expects($this->once())->method('create')->willReturn($option);
        $result = $this->getInstance(['_options' => [['option1']]])->toArray();
        $this->assertArrayHasKey('code', $result);
        $this->assertArrayHasKey('label', $result);
        $this->assertArrayHasKey('show_in_list', $result);
        $this->assertArrayHasKey('_options', $result);
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic::getTypeInstance
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic::__construct
     */
    public function testGetTypeInstance()
    {
        $this->typeFactory->expects($this->once())->method('create');
        $instance = $this->getInstance([]);
        $this->assertEquals($this->type, $instance->getTypeInstance());
        //call twice for memoizing
        $this->assertEquals($this->type, $instance->getTypeInstance());
    }

    /**
     * @param array $data
     * @return Dynamic
     */
    private function getInstance(array $data): Dynamic
    {
        return new Dynamic(
            $this->optionFactory,
            $this->typeFactory,
            $this->stringUtil,
            $this->attribute,
            $data
        );
    }
}
