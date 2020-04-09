<?php
declare(strict_types=1);

namespace App\Model\Test\Unit\Attribute;

use App\Model\Attribute;
use App\Model\Attribute\Serialized;
use App\Model\Attribute\Serialized\Option\Factory as OptionFactory;
use App\Model\Attribute\Serialized\Type\Factory as TypeFactory;
use App\Model\Entity;
use App\Model\Module;
use App\Util\StringUtil;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SerializedTest extends TestCase
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
     * @var Serialized
     */
    private $serialized;
    /**
     * @var Serialized\Type\BaseType | MockObject
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
        $this->type = $this->createMock(Serialized\Type\BaseType::class);
        $this->typeFactory->method('create')->willReturn($this->type);
        $this->serialized = new Serialized(
            $this->optionFactory,
            $this->typeFactory,
            $this->stringUtil,
            $this->attribute,
            []
        );
    }

    /**
     * @covers \App\Model\Attribute\Serialized::getCode
     * @covers \App\Model\Attribute\Serialized::__construct
     */
    public function testGetCode()
    {
        $this->assertEquals('code', $this->getInstance(['code' => 'code'])->getCode());
        $this->assertEquals('', $this->getInstance([])->getCode());
    }

    /**
     * @covers \App\Model\Attribute\Serialized::getLabel
     * @covers \App\Model\Attribute\Serialized::__construct
     */
    public function testGetLabel()
    {
        $this->assertEquals('label', $this->getInstance(['label' => 'label'])->getLabel());
        $this->assertEquals('', $this->getInstance([])->getLabel());
    }

    /**
     * @covers \App\Model\Attribute\Serialized::getType
     * @covers \App\Model\Attribute\Serialized::__construct
     */
    public function testGetType()
    {
        $this->assertEquals('type', $this->getInstance(['type' => 'type'])->getType());
        $this->assertEquals('', $this->getInstance([])->getType());
    }

    /**
     * @covers \App\Model\Attribute\Serialized::isExpanded
     * @covers \App\Model\Attribute\Serialized::__construct
     */
    public function testIsExpanded()
    {
        $this->assertTrue($this->getInstance(['expanded' => '2'])->isExpanded());
        $this->assertFalse($this->getInstance([])->isExpanded());
    }

    /**
     * @covers \App\Model\Attribute\Serialized::isRequired
     * @covers \App\Model\Attribute\Serialized::__construct
     */
    public function testIsRequired()
    {
        $this->assertTrue($this->getInstance(['required' => true])->isRequired());
        $this->assertFalse($this->getInstance([])->isRequired());
    }

    /**
     * @covers \App\Model\Attribute\Serialized::isShowInList
     * @covers \App\Model\Attribute\Serialized::__construct
     */
    public function testIsShowInList()
    {
        $this->assertTrue($this->getInstance(['show_in_list' => true])->isShowInList());
        $this->assertFalse($this->getInstance([])->isShowInList());
    }

    /**
     * @covers \App\Model\Attribute\Serialized::isShowInView
     * @covers \App\Model\Attribute\Serialized::__construct
     */
    public function testIsShowInView()
    {
        $this->assertTrue($this->getInstance(['show_in_view' => true])->isShowInView());
        $this->assertFalse($this->getInstance([])->isShowInView());
    }

    /**
     * @covers \App\Model\Attribute\Serialized::getNote
     * @covers \App\Model\Attribute\Serialized::__construct
     */
    public function testGetNote()
    {
        $this->assertEquals('note', $this->getInstance(['note' => 'note'])->getNote());
        $this->assertEquals('', $this->getInstance([])->getNote());
    }

    /**
     * @covers \App\Model\Attribute\Serialized::getTooltip
     * @covers \App\Model\Attribute\Serialized::__construct
     */
    public function testGetTooltip()
    {
        $this->assertEquals('tooltip', $this->getInstance(['tooltip' => 'tooltip'])->getTooltip());
        $this->assertEquals('', $this->getInstance([])->getTooltip());
    }

    /**
     * @covers \App\Model\Attribute\Serialized::getDefaultValue
     * @covers \App\Model\Attribute\Serialized::__construct
     */
    public function testGetDefaultValue()
    {
        $this->assertEquals('default', $this->getInstance(['default_value' => 'default'])->getDefaultValue());
        $this->assertEquals('', $this->getInstance([])->getDefaultValue());
    }

    /**
     * @covers \App\Model\Attribute\Serialized::getAttribute
     * @covers \App\Model\Attribute\Serialized::__construct
     */
    public function testGetAttribute()
    {
        $this->assertEquals($this->attribute, $this->getInstance([])->getAttribute());
    }

    /**
     * @covers \App\Model\Attribute\Serialized::isManualOptions
     * @covers \App\Model\Attribute\Serialized::__construct
     */
    public function testIsManualOptions()
    {
        $this->type->expects($this->once())->method('isCanHaveOptions')->willReturn(true);
        $this->assertTrue($this->getInstance([])->isManualOptions());
    }

    /**
     * @covers \App\Model\Attribute\Serialized::getOptions
     * @covers \App\Model\Attribute\Serialized::__construct
     */
    public function testGetOptions()
    {
        $this->type->expects($this->once())->method('isCanHaveOptions')->willReturn(true);
        $this->optionFactory->expects($this->exactly(2))->method('create')->willReturn(
            $this->createMock(Serialized\Option::class)
        );
        $this->assertEquals(
            2,
            count($this->getInstance(['_options' => [['option1'], ['option2']]])->getOptions())
        );
    }

    /**
     * @covers \App\Model\Attribute\Serialized::getOptions
     * @covers \App\Model\Attribute\Serialized::__construct
     */
    public function testGetOptionsNotAllowed()
    {
        $this->type->expects($this->once())->method('isCanHaveOptions')->willReturn(false);
        $this->optionFactory->expects($this->exactly(2))->method('create')->willReturn(
            $this->createMock(Serialized\Option::class)
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
     * @covers \App\Model\Attribute\Serialized::getOptionType
     * @covers \App\Model\Attribute\Serialized::__construct
     */
    public function testGetOptionType()
    {
        $this->type->expects($this->once())->method('isCanHaveOptions')->willReturn(true);
        $option1 = $this->createMock(Serialized\Option::class);
        $option1->method('getValue')->willReturn(1);
        $option2 = $this->createMock(Serialized\Option::class);
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
     * @covers \App\Model\Attribute\Serialized::getOptionType
     * @covers \App\Model\Attribute\Serialized::__construct
     */
    public function testGetOptionTypeAllNumbers()
    {
        $this->type->expects($this->once())->method('isCanHaveOptions')->willReturn(true);
        $option1 = $this->createMock(Serialized\Option::class);
        $option1->method('getValue')->willReturn(1);
        $option2 = $this->createMock(Serialized\Option::class);
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
     * @covers \App\Model\Attribute\Serialized::getOptionSourceVirtualType
     * @covers \App\Model\Attribute\Serialized::__construct
     */
    public function testGetOptionSourceVirtualType()
    {
        $this->attribute->method('getCode')->willReturn('attribute_code');
        $entity = $this->createMock(Entity::class);
        $module = $this->createMock(Module::class);
        $this->attribute->method('getEntity')->willReturn($entity);
        $entity->method('getModule')->willReturn($module);
        $entity->method('getNameSingular')->willReturn('entity_name');
        $module->method('getModuleName')->willReturn('ModuleName');
        $this->stringUtil->expects($this->once())->method('glueClassParts')
            ->with([
                'ModuleName',
                'entity_name',
                'Source',
                'attribute_code',
                'serialized_code'
            ])
            ->willReturn('source_model');
        $this->assertEquals('source_model', $this->getInstance(['code' => 'serialized_code'])->getOptionSourceVirtualType());
    }

    /**
     * @covers \App\Model\Attribute\Serialized::toArray
     * @covers \App\Model\Attribute\Serialized::__construct
     */
    public function testToArray()
    {
        $option = $this->createMock(Serialized\Option::class);
        $option->expects($this->once())->method('toArray');
        $this->optionFactory->expects($this->once())->method('create')->willReturn($option);
        $result = $this->getInstance(['_options' => [['option1']]])->toArray();
        $this->assertArrayHasKey('code', $result);
        $this->assertArrayHasKey('label', $result);
        $this->assertArrayHasKey('show_in_list', $result);
        $this->assertArrayHasKey('_options', $result);
    }

    /**
     * @covers \App\Model\Attribute\Serialized::getTypeInstance
     * @covers \App\Model\Attribute\Serialized::__construct
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
     * @return Serialized
     */
    private function getInstance(array $data): Serialized
    {
        return new Serialized(
            $this->optionFactory,
            $this->typeFactory,
            $this->stringUtil,
            $this->attribute,
            $data
        );
    }
}
