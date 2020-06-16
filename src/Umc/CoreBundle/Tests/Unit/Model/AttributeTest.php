<?php

/**
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

namespace App\Umc\CoreBundle\Tests\Unit\Model;

use App\Umc\CoreBundle\Model\Attribute;
use App\Umc\CoreBundle\Model\Attribute\Type\Factory as TypeFactory;
use App\Umc\CoreBundle\Model\Attribute\Option\Factory as OptionFactory;
use App\Umc\CoreBundle\Model\Attribute\Dynamic\Factory as DynamicFactory;
use App\Umc\CoreBundle\Model\Entity;
use App\Umc\CoreBundle\Util\StringUtil;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AttributeTest extends TestCase
{
    /**
     * @var TypeFactory | MockObject
     */
    private $typeFactory;
    /**
     * @var OptionFactory | MockObject
     */
    private $optionFactory;
    /**
     * @var DynamicFactory | MockObject
     */
    private $dynamicFactory;
    /**
     * @var StringUtil | MockObject
     */
    private $stringUtil;
    /**
     * @var Entity | MockObject
     */
    private $entity;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->typeFactory = $this->createMock(Attribute\Type\Factory::class);
        $this->optionFactory = $this->createMock(OptionFactory::class);
        $this->dynamicFactory = $this->createMock(DynamicFactory::class);
        $this->stringUtil = $this->createMock(StringUtil::class);
        $this->entity = $this->createMock(Entity::class);
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute::__construct
     * @covers \App\Umc\CoreBundle\Model\Attribute::toArray()
     */
    public function testToArray()
    {
        $dynamic = $this->createMock(Attribute\Dynamic::class);
        $dynamic->expects($this->once())->method('toArray');
        $option = $this->createMock(Attribute\Option::class);
        $option->expects($this->once())->method('toArray');
        $this->dynamicFactory->expects($this->once())->method('create')->willReturn($dynamic);
        $this->optionFactory->expects($this->once())->method('create')->willReturn($option);
        $result = $this->getInstance([
            '_option' => [[]],
            '_dynamic' => [[]]
        ])->toArray();
        $this->assertArrayHasKey('code', $result);
        $this->assertArrayHasKey('label', $result);
        $this->assertArrayHasKey('_option', $result);
        $this->assertArrayHasKey('_dynamic', $result);
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute::__construct
     * @covers \App\Umc\CoreBundle\Model\Attribute::getTypeInstance
     */
    public function testGetTypeInstance()
    {
        $typeInstance = $this->createMock(Attribute\Type\BaseType::class);
        $this->typeFactory->expects($this->once())->method('create')->willReturn($typeInstance);
        //test memoizing
        $instance = $this->getInstance([]);
        $attrTypeInstance = $instance->getTypeInstance();
        $instance->getTypeInstance();
        $this->assertEquals($typeInstance, $attrTypeInstance);
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute::getEntity
     * @covers \App\Umc\CoreBundle\Model\Attribute::__construct
     */
    public function testGetEntity()
    {
        $this->assertEquals($this->entity, $this->getInstance([])->getEntity());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute::getCode
     * @covers \App\Umc\CoreBundle\Model\Attribute::__construct
     */
    public function testGetCode()
    {
        $this->assertEquals('code', $this->getInstance(['code' => 'code'])->getCode());
        $this->assertEquals('', $this->getInstance([])->getCode());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute::getRawDefaultValue
     * @covers \App\Umc\CoreBundle\Model\Attribute::__construct
     */
    public function testGetRawDefaultValue()
    {
        $this->assertEquals('default', $this->getInstance(['default_value' => 'default'])->getRawDefaultValue());
        $this->assertEquals('', $this->getInstance([])->getRawDefaultValue());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute::getLabel
     * @covers \App\Umc\CoreBundle\Model\Attribute::__construct
     */
    public function testGetLabel()
    {
        $this->assertEquals('label', $this->getInstance(['label' => 'label'])->getLabel());
        $this->assertEquals('', $this->getInstance([])->getLabel());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute::getType
     * @covers \App\Umc\CoreBundle\Model\Attribute::__construct
     */
    public function testGetType()
    {
        $this->assertEquals('type', $this->getInstance(['type' => 'type'])->getType());
        $this->assertEquals('', $this->getInstance([])->getCode());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute::isName
     * @covers \App\Umc\CoreBundle\Model\Attribute::__construct
     */
    public function testIsName()
    {
        $type = $this->createMock(Attribute\Type\BaseType::class);
        $type->expects($this->once())->method('hasFlag')->with('can_be_name')->willReturn(true);
        $this->typeFactory->expects($this->once())->method('create')->willReturn($type);
        $this->assertTrue($this->getInstance(['is_name' => 1])->isName());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute::isName
     * @covers \App\Umc\CoreBundle\Model\Attribute::__construct
     */
    public function testIsNameNotAllowedByType()
    {
        $type = $this->createMock(Attribute\Type\BaseType::class);
        $type->expects($this->once())->method('hasFlag')->with('can_be_name')->willReturn(false);
        $this->typeFactory->expects($this->once())->method('create')->willReturn($type);
        $this->assertFalse($this->getInstance(['is_name' => 1])->isName());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute::isName
     * @covers \App\Umc\CoreBundle\Model\Attribute::__construct
     */
    public function testIsNameNotAllowed()
    {
        $this->assertFalse($this->getInstance(['is_name' => false])->isName());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute::isDynamic
     * @covers \App\Umc\CoreBundle\Model\Attribute::__construct
     */
    public function testIsDynamic()
    {
        $this->assertTrue($this->getInstance(['type' => 'dynamic'])->isDynamic());
        $this->assertFalse($this->getInstance([])->isDynamic());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute::isRequired
     * @covers \App\Umc\CoreBundle\Model\Attribute::__construct
     */
    public function testIsRequired()
    {
        $type = $this->createMock(Attribute\Type\BaseType::class);
        $type->expects($this->once())->method('hasFlag')->with('can_be_required')->willReturn(true);
        $this->typeFactory->expects($this->once())->method('create')->willReturn($type);
        $this->assertTrue($this->getInstance(['required' => 1])->isRequired());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute::isRequired
     * @covers \App\Umc\CoreBundle\Model\Attribute::__construct
     */
    public function testIsRequiredNotAllowedByType()
    {
        $type = $this->createMock(Attribute\Type\BaseType::class);
        $type->expects($this->once())->method('hasFlag')->with('can_be_required')->willReturn(false);
        $this->typeFactory->method('create')->willReturn($type);
        $this->assertFalse($this->getInstance(['required' => 1])->isRequired());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute::isRequired
     * @covers \App\Umc\CoreBundle\Model\Attribute::__construct
     */
    public function testIsRequiredNotAllowed()
    {
        $type = $this->createMock(Attribute\Type\BaseType::class);
        $type->expects($this->never())->method('hasFlag')->with('can_be_required');
        $this->typeFactory->method('create')->willReturn($type);
        $this->assertFalse($this->getInstance(['required' => false])->isRequired());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute::getOptions
     * @covers \App\Umc\CoreBundle\Model\Attribute::__construct
     */
    public function testGetOptions()
    {
        $option = $this->createMock(Attribute\Option::class);
        $this->optionFactory->expects($this->once())->method('create')->willReturn($option);
        $type = $this->createMock(Attribute\Type\BaseType::class);
        $type->expects($this->once())->method('hasFlag')->with('manual_options')->willReturn(true);
        $this->typeFactory->method('create')->willReturn($type);
        $attribute = $this->getInstance([
            '_option' => [[]]
        ]);
        $this->assertEquals([$option], $attribute->getOptions());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute::getOptions
     * @covers \App\Umc\CoreBundle\Model\Attribute::__construct
     */
    public function testGetOptionsNotAllowed()
    {
        $option = $this->createMock(Attribute\Option::class);
        $this->optionFactory->expects($this->once())->method('create')->willReturn($option);
        $type = $this->createMock(Attribute\Type\BaseType::class);
        $type->expects($this->once())->method('hasFlag')->with('manual_options')->willReturn(false);
        $this->typeFactory->method('create')->willReturn($type);
        $attribute = $this->getInstance([
            '_option' => [[]]
        ]);
        $this->assertEquals([], $attribute->getOptions());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute::getDefaultValue
     * @covers \App\Umc\CoreBundle\Model\Attribute::__construct
     */
    public function testGetDefaultValue()
    {
        $typeInstance = $this->createMock(Attribute\Type\BaseType::class);
        $typeInstance->expects($this->once())->method('getDefaultValue')->willReturn('default');
        $this->typeFactory->expects($this->once())->method('create')->willReturn($typeInstance);
        $attribute = $this->getInstance(['default_value' => 'default']);
        $this->assertEquals('default', $attribute->getDefaultValue());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute::isShowInList
     * @covers \App\Umc\CoreBundle\Model\Attribute::__construct
     */
    public function testIsShowInList()
    {
        $this->entity->method('isFrontend')->willReturn(true);
        $attribute = $this->getInstance(['show_in_list' => 1]);
        $this->assertTrue($attribute->isShowInList());
        //call twice to test memoizing
        $this->assertTrue($attribute->isShowInList());

        $attribute = $this->getInstance(['show_in_list' => 0]);
        $this->assertFalse($attribute->isShowInList());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute::isShowInList
     * @covers \App\Umc\CoreBundle\Model\Attribute::__construct
     */
    public function testIsShowInListNotAllowedByEntity()
    {
        $this->entity->method('isFrontend')->willReturn(false);
        $attribute = $this->getInstance(['show_in_list' => 1]);
        $this->assertFalse($attribute->isShowInList());
        //call twice to test memoizing
        $this->assertFalse($attribute->isShowInList());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute::isShowInView
     * @covers \App\Umc\CoreBundle\Model\Attribute::__construct
     */
    public function testIsShowInView()
    {
        $this->entity->method('isFrontend')->willReturn(true);
        $attribute = $this->getInstance(['show_in_view' => 1]);
        $this->assertTrue($attribute->isShowInView());
        //call twice to test memoizing
        $this->assertTrue($attribute->isShowInView());

        $attribute = $this->getInstance(['show_in_view' => 0]);
        $this->assertFalse($attribute->isShowInView());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute::isShowInView
     * @covers \App\Umc\CoreBundle\Model\Attribute::__construct
     */
    public function testIsShowInViewNotAllowedByEntity()
    {
        $this->entity->method('isFrontend')->willReturn(false);
        $attribute = $this->getInstance(['show_in_view' => 1]);
        $this->assertFalse($attribute->isShowInView());
        //call twice to test memoizing
        $this->assertFalse($attribute->isShowInView());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute::getDynamic
     * @covers \App\Umc\CoreBundle\Model\Attribute::__construct
     */
    public function testGetDynamic()
    {
        $dynamic = $this->createMock(Attribute\Dynamic::class);
        $this->dynamicFactory->expects($this->once())->method('create')->willReturn($dynamic);
        $this->assertEquals(
            [$dynamic],
            $this->getInstance(['_dynamic' => [[]], 'type' => 'dynamic'])->getDynamic()
        );
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute::getDynamic
     * @covers \App\Umc\CoreBundle\Model\Attribute::__construct
     */
    public function testGetDynamicNotDynamic()
    {
        $dynamic = $this->createMock(Attribute\Dynamic::class);
        $this->dynamicFactory->expects($this->once())->method('create')->willReturn($dynamic);
        $this->assertEquals(
            [],
            $this->getInstance(['_dynamic' => [[]], 'type' => 'text'])->getDynamic()
        );
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute::getDynamicWithOptions
     * @covers \App\Umc\CoreBundle\Model\Attribute::__construct
     */
    public function testGetDynamicWithOptions()
    {
        $dynamic1 = $this->createMock(Attribute\Dynamic::class);
        $dynamic1->method('isManualOptions')->willReturn(false);
        $dynamic2 = $this->createMock(Attribute\Dynamic::class);
        $dynamic2->method('isManualOptions')->willReturn(true);
        $this->dynamicFactory->expects($this->exactly(2))->method('create')
            ->willReturnOnConsecutiveCalls($dynamic1, $dynamic2);
        $this->assertEquals(
            [$dynamic2],
            $this->getInstance(['_dynamic' => [[], []], 'type' => 'dynamic'])->getDynamicWithOptions()
        );
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute::isManualOptions
     * @covers \App\Umc\CoreBundle\Model\Attribute::__construct
     */
    public function testisManualOptions()
    {
        $typeInstance = $this->createMock(Attribute\Type\BaseType::class);
        $typeInstance->expects($this->once())->method('hasFlag')->with('manual_options')->willReturn(true);
        $this->typeFactory->expects($this->once())->method('create')->willReturn($typeInstance);
        $this->assertTrue($this->getInstance([])->isManualOptions());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute::areOptionsNumerical
     * @covers \App\Umc\CoreBundle\Model\Attribute::__construct
     */
    public function testGetOptionType()
    {
        $option1 = $this->createMock(Attribute\Option::class);
        $option1->expects($this->once())->method('getValue')->willReturn(10);
        $option2 = $this->createMock(Attribute\Option::class);
        $option2->expects($this->once())->method('getValue')->willReturn('value');
        $option3 = $this->createMock(Attribute\Option::class);
        $option3->expects($this->never())->method('getValue');
        $this->optionFactory->expects($this->exactly(3))->method('create')
            ->willReturnOnConsecutiveCalls($option1, $option2, $option3);
        $typeInstance = $this->createMock(Attribute\Type\BaseType::class);
        $typeInstance->method('hasFlag')->with('manual_options')->willReturn(true);
        $this->typeFactory->expects($this->once())->method('create')->willReturn($typeInstance);
        $instance = $this->getInstance(['_option' => [[], [], []]]);
        $this->assertFalse($instance->areOptionsNumerical());
        //call twice to test memoizing
        $this->assertFalse($instance->areOptionsNumerical());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute::getFlags
     * @covers \App\Umc\CoreBundle\Model\Attribute::__construct
     */
    public function testGetFlags()
    {
        $this->entity->method('isFrontend')->willReturn(true);
        $typeInstance = $this->createMock(Attribute\Type\BaseType::class);
        $typeInstance->expects($this->once())->method('getFlags')->willReturn(['flag1', 'flag2']);
        $this->typeFactory->expects($this->once())->method('create')->willReturn($typeInstance);
        $attribute = $this->getInstance(['show_in_list' => true]);
        $expected = ['show_in_list', 'flag1', 'flag2'];
        $this->assertEquals($expected, $attribute->getFlags());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute::hasFlag
     * @covers \App\Umc\CoreBundle\Model\Attribute::getFlags
     * @covers \App\Umc\CoreBundle\Model\Attribute::__construct
     */
    public function testHasFlag()
    {
        $this->entity->method('isFrontend')->willReturn(true);
        $typeInstance = $this->createMock(Attribute\Type\BaseType::class);
        $typeInstance->expects($this->once())->method('getFlags')->willReturn(['flag3', 'flag4']);
        $this->typeFactory->expects($this->once())->method('create')->willReturn($typeInstance);
        $attribute = $this->getInstance(['show_in_list' => true]);
        $this->assertTrue($attribute->hasFlag('show_in_list'));
        $this->assertTrue($attribute->hasFlag('flag3'));
        $this->assertFalse($attribute->hasFlag('dummy'));
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute::getFlagSuffixes
     * @covers \App\Umc\CoreBundle\Model\Attribute::__construct
     */
    public function testGetFlagSuffixes()
    {
        $typeInstance = $this->createMock(Attribute\Type\BaseType::class);
        $typeInstance->expects($this->once())->method('getFlags')
            ->willReturn(['prefix_flag', 'prefix_flag2', 'prefix2_flag']);
        $this->typeFactory->expects($this->once())->method('create')->willReturn($typeInstance);
        $attribute = $this->getInstance([]);
        $this->assertEquals(['flag', 'flag2'], $attribute->getFlagSuffixes('prefix_'));
        $this->assertEquals(['flag'], $attribute->getFlagSuffixes('prefix2_'));
        $this->assertEquals([], $attribute->getFlagSuffixes('prefix3_'));
    }

    /**
     * @param $data
     * @return Attribute
     */
    private function getInstance($data): Attribute
    {
        return new Attribute(
            $this->typeFactory,
            $this->optionFactory,
            $this->dynamicFactory,
            $this->stringUtil,
            $this->entity,
            $data
        );
    }
}
