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
