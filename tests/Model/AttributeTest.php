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
use App\Model\Attribute\Type\Factory as TypeFactory;
use App\Model\Attribute\Option\Factory as OptionFactory;
use App\Model\Attribute\Serialized\Factory as SerializedFactory;
use App\Model\Entity;
use App\Model\Module;
use App\Util\StringUtil;
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
     * @var SerializedFactory | MockObject
     */
    private $serializedFactory;
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
    protected function setUp()
    {
        $this->typeFactory = $this->createMock(Attribute\Type\Factory::class);
        $this->optionFactory = $this->createMock(OptionFactory::class);
        $this->serializedFactory = $this->createMock(SerializedFactory::class);
        $this->stringUtil = $this->createMock(StringUtil::class);
        $this->entity = $this->createMock(Entity::class);
    }

    /**
     * @covers \App\Model\Attribute::toArray()
     */
    public function testToArray()
    {
        $serialized = $this->createMock(Attribute\Serialized::class);
        $serialized->expects($this->once())->method('toArray');
        $option = $this->createMock(Attribute\Option::class);
        $option->expects($this->once())->method('toArray');
        $this->serializedFactory->expects($this->once())->method('create')->willReturn($serialized);
        $this->optionFactory->expects($this->once())->method('create')->willReturn($option);
        $result = $this->getInstance([
            '_options' => [[]],
            '_serialized' => [[]]
        ])->toArray();
        $this->assertArrayHasKey('code', $result);
        $this->assertArrayHasKey('label', $result);
        $this->assertArrayHasKey('_options', $result);
        $this->assertArrayHasKey('_serialized', $result);
    }

    /**
     * @covers \App\Model\Attribute::__construct
     * @covers \App\Model\Attribute::getTypeInstance
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
     * @covers \App\Model\Attribute::getEntity
     * @covers \App\Model\Attribute::__construct
     */
    public function testGetEntity()
    {
        $this->assertEquals($this->entity, $this->getInstance([])->getEntity());
    }

    /**
     * @covers \App\Model\Attribute::getCode
     * @covers \App\Model\Attribute::__construct
     */
    public function testGetCode()
    {
        $this->assertEquals('code', $this->getInstance(['code' => 'code'])->getCode());
        $this->assertEquals('', $this->getInstance([])->getCode());
    }

    /**
     * @covers \App\Model\Attribute::getTooltip
     * @covers \App\Model\Attribute::__construct
     */
    public function testGetTooltip()
    {
        $this->assertEquals('tooltip', $this->getInstance(['tooltip' => 'tooltip'])->getTooltip());
        $this->assertEquals('', $this->getInstance([])->getTooltip());
    }

    /**
     * @covers \App\Model\Attribute::getRawDefaultValue
     * @covers \App\Model\Attribute::__construct
     */
    public function testGetRawDefaultValue()
    {
        $this->assertEquals('default', $this->getInstance(['default_value' => 'default'])->getRawDefaultValue());
        $this->assertEquals('', $this->getInstance([])->getRawDefaultValue());
    }

    /**
     * @covers \App\Model\Attribute::getLabel
     * @covers \App\Model\Attribute::__construct
     */
    public function testGetLabel()
    {
        $this->assertEquals('label', $this->getInstance(['label' => 'label'])->getLabel());
        $this->assertEquals('', $this->getInstance([])->getLabel());
    }

    /**
     * @covers \App\Model\Attribute::getType
     * @covers \App\Model\Attribute::__construct
     */
    public function testGetType()
    {
        $this->assertEquals('type', $this->getInstance(['type' => 'type'])->getType());
        $this->assertEquals('', $this->getInstance([])->getCode());
    }

    /**
     * @covers \App\Model\Attribute::isName
     * @covers \App\Model\Attribute::__construct
     */
    public function testIsName()
    {
        $type = $this->createMock(Attribute\Type\BaseType::class);
        $type->expects($this->once())->method('isCanBeName')->willReturn(true);
        $this->typeFactory->expects($this->once())->method('create')->willReturn($type);
        $this->assertTrue($this->getInstance(['is_name' => 1])->isName());
    }

    /**
     * @covers \App\Model\Attribute::isName
     * @covers \App\Model\Attribute::__construct
     */
    public function testIsNameNotAllowedByType()
    {
        $type = $this->createMock(Attribute\Type\BaseType::class);
        $type->expects($this->once())->method('isCanBeName')->willReturn(false);
        $this->typeFactory->expects($this->once())->method('create')->willReturn($type);
        $this->assertFalse($this->getInstance(['is_name' => 1])->isName());
    }

    /**
     * @covers \App\Model\Attribute::isName
     * @covers \App\Model\Attribute::__construct
     */
    public function testIsNameNotAllowed()
    {
        $this->assertFalse($this->getInstance(['is_name' => false])->isName());
    }

    /**
     * @covers \App\Model\Attribute::isFullText
     * @covers \App\Model\Attribute::__construct
     */
    public function testIsFullText()
    {
        $type = $this->createMock(Attribute\Type\BaseType::class);
        $type->expects($this->once())->method('isFullText')->willReturn(true);
        $this->typeFactory->expects($this->once())->method('create')->willReturn($type);
        $this->assertTrue($this->getInstance(['full_text' => 1])->isFullText());
    }

    /**
     * @covers \App\Model\Attribute::isFullText
     * @covers \App\Model\Attribute::__construct
     */
    public function testIsFullTextNotAllowedByType()
    {
        $type = $this->createMock(Attribute\Type\BaseType::class);
        $type->expects($this->once())->method('isFullText')->willReturn(false);
        $this->typeFactory->expects($this->once())->method('create')->willReturn($type);
        $this->assertFalse($this->getInstance(['full_text' => 1])->isFullText());
    }

    /**
     * @covers \App\Model\Attribute::isFullText
     * @covers \App\Model\Attribute::__construct
     */
    public function testIsFulltextNotAllowed()
    {
        $this->assertFalse($this->getInstance(['full_text' => false])->isFullText());
    }

    /**
     * @covers \App\Model\Attribute::isExpanded
     * @covers \App\Model\Attribute::__construct
     */
    public function testIsExpanded()
    {
        $this->assertTrue($this->getInstance(['expanded' => 1])->isExpanded());
        $this->assertFalse($this->getInstance([])->isExpanded());
    }

    /**
     * @covers \App\Model\Attribute::isDynamic
     * @covers \App\Model\Attribute::__construct
     */
    public function testIsSerialized()
    {
        $this->assertTrue($this->getInstance(['type' => 'serialized'])->isDynamic());
        $this->assertFalse($this->getInstance([])->isDynamic());
    }

    /**
     * @covers \App\Model\Attribute::isRequired
     * @covers \App\Model\Attribute::__construct
     */
    public function testIsRequired()
    {
        $type = $this->createMock(Attribute\Type\BaseType::class);
        $type->expects($this->once())->method('isCanBeRequired')->willReturn(true);
        $this->typeFactory->expects($this->once())->method('create')->willReturn($type);
        $this->assertTrue($this->getInstance(['required' => 1])->isRequired());
    }

    /**
     * @covers \App\Model\Attribute::isRequired
     * @covers \App\Model\Attribute::__construct
     */
    public function testIsRequiredNotAllowedByType()
    {
        $type = $this->createMock(Attribute\Type\BaseType::class);
        $type->expects($this->once())->method('isCanBeRequired')->willReturn(false);
        $this->typeFactory->method('create')->willReturn($type);
        $this->assertFalse($this->getInstance(['required' => 1])->isRequired());
    }

    /**
     * @covers \App\Model\Attribute::isRequired
     * @covers \App\Model\Attribute::__construct
     */
    public function testIsRequiredNotAllowed()
    {
        $type = $this->createMock(Attribute\Type\BaseType::class);
        $type->expects($this->never())->method('isCanBeRequired')->willReturn(true);
        $this->typeFactory->method('create')->willReturn($type);
        $this->assertFalse($this->getInstance(['required' => false])->isRequired());
    }

    /**
     * @covers \App\Model\Attribute::getOptions
     * @covers \App\Model\Attribute::__construct
     */
    public function testGetOptions()
    {
        $option = $this->createMock(Attribute\Option::class);
        $this->optionFactory->expects($this->once())->method('create')->willReturn($option);
        $type = $this->createMock(Attribute\Type\BaseType::class);
        $type->expects($this->once())->method('isCanHaveOptions')->willReturn(true);
        $this->typeFactory->method('create')->willReturn($type);
        $attribute = $this->getInstance([
            '_options' => [[]]
        ]);
        $this->assertEquals([$option], $attribute->getOptions());
    }

    /**
     * @covers \App\Model\Attribute::getOptions
     * @covers \App\Model\Attribute::__construct
     */
    public function testGetOptionsNotAllowed()
    {
        $option = $this->createMock(Attribute\Option::class);
        $this->optionFactory->expects($this->once())->method('create')->willReturn($option);
        $type = $this->createMock(Attribute\Type\BaseType::class);
        $type->expects($this->once())->method('isCanHaveOptions')->willReturn(false);
        $this->typeFactory->method('create')->willReturn($type);
        $attribute = $this->getInstance([
            '_options' => [[]]
        ]);
        $this->assertEquals([], $attribute->getOptions());
    }

    /**
     * @covers \App\Model\Attribute::getNote
     * @covers \App\Model\Attribute::__construct
     */
    public function testGetNote()
    {
        $this->assertEquals('note', $this->getInstance(['note' => 'note'])->getNote());
        $this->assertEquals('', $this->getInstance([])->getNote());
    }

    /**
     * @covers \App\Model\Attribute::isAdminGrid
     * @covers \App\Model\Attribute::__construct
     */
    public function testGetAdminGrid()
    {
        $type = $this->createMock(Attribute\Type\BaseType::class);
        $this->typeFactory->expects($this->once())->method('create')->willReturn($type);
        $type->method('isCanShowInGrid')->willReturn(true);
        $this->assertTrue($this->getInstance(['admin_grid' => 1])->isAdminGrid());
    }

    /**
     * @covers \App\Model\Attribute::isAdminGrid
     * @covers \App\Model\Attribute::__construct
     */
    public function testIsAdminGridNotAllowedByType()
    {
        $type = $this->createMock(Attribute\Type\BaseType::class);
        $this->typeFactory->expects($this->once())->method('create')->willReturn($type);
        $type->method('isCanShowInGrid')->willReturn(false);
        $this->assertFalse($this->getInstance(['admin_grid' => true])->isAdminGrid());
    }

    /**
     * @covers \App\Model\Attribute::isAdminGrid
     * @covers \App\Model\Attribute::__construct
     */
    public function testIsAdminGridNotAllowed()
    {
        $type = $this->createMock(Attribute\Type\BaseType::class);
        $this->typeFactory->method('create')->willReturn($type);
        $type->expects($this->never())->method('isCanShowInGrid');
        $this->assertFalse($this->getInstance(['admin_grid' => false])->isAdminGrid());
    }

    /**
     * @covers \App\Model\Attribute::isAdminGridHidden
     * @covers \App\Model\Attribute::__construct
     */
    public function testGetAdminGridHidden()
    {
        $type = $this->createMock(Attribute\Type\BaseType::class);
        $this->typeFactory->expects($this->once())->method('create')->willReturn($type);
        $type->method('isCanShowInGrid')->willReturn(true);
        $this->assertTrue($this->getInstance(['admin_grid' => 1, 'admin_grid_hidden' => true])->isAdminGridHidden());
    }

    /**
     * @covers \App\Model\Attribute::isAdminGridFilter
     * @covers \App\Model\Attribute::__construct
     */
    public function testGetAdminGridFilter()
    {
        $type = $this->createMock(Attribute\Type\BaseType::class);
        $type->method('isCanFilterInGrid')->willReturn(true);
        $type->method('isCanShowInGrid')->willReturn(true);
        $this->typeFactory->method('create')->willReturn($type);
        $attribute = $this->getInstance(['admin_grid' => '1', 'admin_grid_filter' => 1]);
        $this->assertTrue($attribute->isAdminGridFilter());
        $attribute = $this->getInstance([]);
        $this->assertFalse($attribute->isAdminGridFilter());
        $attribute = $this->getInstance(['admin_grid' => 0, 'admin_grid_filter' => 1]);
        $this->assertFalse($attribute->isAdminGridFilter());
        $attribute = $this->getInstance(['admin_grid' => 1, 'admin_grid_filter' => 0]);
        $this->assertFalse($attribute->isAdminGridFilter());
        $attribute = $this->getInstance(['admin_grid' => 0, 'admin_grid_filter' => 0]);
        $this->assertFalse($attribute->isAdminGridFilter());
    }

    /**
     * @covers \App\Model\Attribute::isAdminGridFilter
     * @covers \App\Model\Attribute::__construct
     */
    public function testGetAdminGridFilterNotAllowed()
    {
        $type = $this->createMock(Attribute\Type\BaseType::class);
        $type->method('isCanFilterInGrid')->willReturn(false);
        $type->method('isCanShowInGrid')->willReturn(true);
        $this->typeFactory->method('create')->willReturn($type);
        $attribute = $this->getInstance(['admin_grid' => '1', 'admin_grid_filter' => 1]);
        $this->assertFalse($attribute->isAdminGridFilter());
    }

    /**
     * @covers \App\Model\Attribute::getDefaultValue
     * @covers \App\Model\Attribute::__construct
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
     * @covers \App\Model\Attribute::isShowInList
     * @covers \App\Model\Attribute::__construct
     */
    public function testIsShowInList()
    {
        $this->entity->method('isFrontendList')->willReturn(true);
        $attribute = $this->getInstance(['show_in_list' => 1]);
        $this->assertTrue($attribute->isShowInList());
        //call twice to test memoizing
        $this->assertTrue($attribute->isShowInList());

        $attribute = $this->getInstance(['show_in_list' => 0]);
        $this->assertFalse($attribute->isShowInList());
    }

    /**
     * @covers \App\Model\Attribute::isShowInList
     * @covers \App\Model\Attribute::__construct
     */
    public function testIsShowInListNotAllowedByEntity()
    {
        $this->entity->method('isFrontendList')->willReturn(false);
        $attribute = $this->getInstance(['show_in_list' => 1]);
        $this->assertFalse($attribute->isShowInList());
        //call twice to test memoizing
        $this->assertFalse($attribute->isShowInList());
    }

    /**
     * @covers \App\Model\Attribute::isShowInView
     * @covers \App\Model\Attribute::__construct
     */
    public function testIsShowInView()
    {
        $this->entity->method('isFrontendView')->willReturn(true);
        $attribute = $this->getInstance(['show_in_view' => 1]);
        $this->assertTrue($attribute->isShowInView());
        //call twice to test memoizing
        $this->assertTrue($attribute->isShowInView());

        $attribute = $this->getInstance(['show_in_view' => 0]);
        $this->assertFalse($attribute->isShowInView());
    }

    /**
     * @covers \App\Model\Attribute::isShowInView
     * @covers \App\Model\Attribute::__construct
     */
    public function testIsShowInViewNotAllowedByEntity()
    {
        $this->entity->method('isFrontendView')->willReturn(false);
        $attribute = $this->getInstance(['show_in_view' => 1]);
        $this->assertFalse($attribute->isShowInView());
        //call twice to test memoizing
        $this->assertFalse($attribute->isShowInView());
    }

    /**
     * @covers \App\Model\Attribute::getDynamic
     * @covers \App\Model\Attribute::__construct
     */
    public function testGetSerialized()
    {
        $serialized = $this->createMock(Attribute\Serialized::class);
        $this->serializedFactory->expects($this->once())->method('create')->willReturn($serialized);
        $this->assertEquals(
            [$serialized],
            $this->getInstance(['_serialized' => [[]], 'type' => 'serialized'])->getDynamic()
        );
    }

    /**
     * @covers \App\Model\Attribute::getDynamic
     * @covers \App\Model\Attribute::__construct
     */
    public function testGetSerializedNotSerialized()
    {
        $serialized = $this->createMock(Attribute\Serialized::class);
        $this->serializedFactory->expects($this->once())->method('create')->willReturn($serialized);
        $this->assertEquals(
            [],
            $this->getInstance(['_serialized' => [[]], 'type' => 'text'])->getDynamic()
        );
    }

    /**
     * @covers \App\Model\Attribute::getDynamicWithOptions
     * @covers \App\Model\Attribute::__construct
     */
    public function testGetSerializedWithOptions()
    {
        $serialized1 = $this->createMock(Attribute\Serialized::class);
        $serialized1->method('isManualOptions')->willReturn(false);
        $serialized2 = $this->createMock(Attribute\Serialized::class);
        $serialized2->method('isManualOptions')->willReturn(true);
        $this->serializedFactory->expects($this->exactly(2))->method('create')
            ->willReturnOnConsecutiveCalls($serialized1, $serialized2);
        $this->assertEquals(
            [$serialized2],
            $this->getInstance(['_serialized' => [[], []], 'type' => 'serialized'])->getDynamicWithOptions()
        );
    }

    /**
     * @covers \App\Model\Attribute::isUpload
     * @covers \App\Model\Attribute::__construct
     */
    public function testIsUpload()
    {
        $typeInstance = $this->createMock(Attribute\Type\BaseType::class);
        $typeInstance->expects($this->once())->method('isUpload')->willReturn(true);
        $this->typeFactory->expects($this->once())->method('create')->willReturn($typeInstance);
        $this->assertTrue($this->getInstance([])->isUpload());
    }

    /**
     * @covers \App\Model\Attribute::isManualOptions
     * @covers \App\Model\Attribute::__construct
     */
    public function testIsCanHaveOptions()
    {
        $typeInstance = $this->createMock(Attribute\Type\BaseType::class);
        $typeInstance->expects($this->once())->method('isCanHaveOptions')->willReturn(true);
        $this->typeFactory->expects($this->once())->method('create')->willReturn($typeInstance);
        $this->assertTrue($this->getInstance([])->isManualOptions());
    }

    /**
     * @covers \App\Model\Attribute::getOptionType
     * @covers \App\Model\Attribute::__construct
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
        $typeInstance->method('isCanHaveOptions')->willReturn(true);
        $this->typeFactory->expects($this->once())->method('create')->willReturn($typeInstance);
        $instance = $this->getInstance(['_options' => [[], [], []]]);
        $this->assertEquals('string', $instance->getOptionType());
        //call twice to test memoizing
        $this->assertEquals('string', $instance->getOptionType());
    }

    /**
     * @covers \App\Model\Attribute::getOptionType
     * @covers \App\Model\Attribute::__construct
     */
    public function testGetOptionTypeAllNumbers()
    {
        $option1 = $this->createMock(Attribute\Option::class);
        $option1->expects($this->once())->method('getValue')->willReturn(10);
        $option2 = $this->createMock(Attribute\Option::class);
        $option2->expects($this->once())->method('getValue')->willReturn(20);
        $option3 = $this->createMock(Attribute\Option::class);
        $option3->expects($this->once())->method('getValue')->willReturn(30);
        $this->optionFactory->expects($this->exactly(3))->method('create')
            ->willReturnOnConsecutiveCalls($option1, $option2, $option3);
        $typeInstance = $this->createMock(Attribute\Type\BaseType::class);
        $typeInstance->method('isCanHaveOptions')->willReturn(true);
        $this->typeFactory->expects($this->once())->method('create')->willReturn($typeInstance);
        $instance = $this->getInstance(['_options' => [[], [], []]]);
        $this->assertEquals('number', $instance->getOptionType());
        //call twice to test memoizing
        $this->assertEquals('number', $instance->getOptionType());
    }

    /**
     * @covers \App\Model\Attribute::getOptionSourceVirtualType
     * @covers \App\Model\Attribute::__construct
     */
    public function testGetOptionSourceVirtualType()
    {
        $module = $this->createMock(Module::class);
        $this->entity->method('getNameSingular')->willReturn('Name');
        $module->expects($this->once())->method('getModuleName')->willReturn('Md');
        $this->entity->method('getModule')->willReturn($module);
        $expected = ['Md', 'Name', 'Source', 'code'];
        $this->stringUtil->expects($this->once())->method('glueClassParts')->with($expected, '')->willReturn('type');
        $this->assertEquals('type', $this->getInstance(['code' => 'code'])->getOptionSourceVirtualType());
    }

    /**
     * @covers \App\Model\Attribute::isMultiple
     * @covers \App\Model\Attribute::__construct
     */
    public function testIsMultiple()
    {
        $typeInstance = $this->createMock(Attribute\Type\BaseType::class);
        $typeInstance->expects($this->once())->method('isMultiple')->willReturn(true);
        $this->typeFactory->expects($this->once())->method('create')->willReturn($typeInstance);
        $this->assertTrue($this->getInstance([])->isMultiple());
    }

    /**
     * @covers \App\Model\Attribute::isProductAttribute
     * @covers \App\Model\Attribute::__construct
     */
    public function testIsProductAttribute()
    {
        $typeInstance = $this->createMock(Attribute\Type\BaseType::class);
        $typeInstance->expects($this->once())->method('isProductAttribute')->willReturn(true);
        $this->typeFactory->expects($this->once())->method('create')->willReturn($typeInstance);
        $this->assertTrue($this->getInstance([])->isProductAttribute());
    }

    /**
     * @covers \App\Model\Attribute::isProductAttributeSet
     * @covers \App\Model\Attribute::__construct
     */
    public function testIsProductAttributeSet()
    {
        $typeInstance = $this->createMock(Attribute\Type\BaseType::class);
        $typeInstance->expects($this->once())->method('isProductAttributeSet')->willReturn(true);
        $this->typeFactory->expects($this->once())->method('create')->willReturn($typeInstance);
        $this->assertTrue($this->getInstance([])->isProductAttributeSet());
    }

    /**
     * @covers \App\Model\Attribute::getProcessorTypes
     * @covers \App\Model\Attribute::__construct
     */
    public function testGetProcessorType()
    {
        $typeInstance = $this->createMock(Attribute\Type\BaseType::class);
        $typeInstance->expects($this->once())->method('getProcessorTypes')->willReturn(['processor']);
        $this->typeFactory->expects($this->once())->method('create')->willReturn($typeInstance);
        $this->assertEquals(['processor'], $this->getInstance([])->getProcessorTypes('type'));
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
            $this->serializedFactory,
            $this->stringUtil,
            $this->entity,
            $data
        );
    }
}
