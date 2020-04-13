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
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class AttributeTest extends TestCase
{
    /**
     * @var TypeFactory | MockObject
     */
    private $typeFactory;
    /**
     * @var OptionFactory
     */
    private $optionFactory;
    /**
     * @var SerializedFactory
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
    protected function setUp(): void
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
        $entityArray = $this->attribute->toArray();
        $this->assertArrayHasKey('label', $entityArray);
        $this->assertArrayNotHasKey('dummy', $entityArray);
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
        $this->assertTrue($this->getInstance(['name' => 1])->isName());
        $this->assertFalse($this->getInstance([])->isName());
    }

    /**
     * @covers \App\Model\Attribute::getRequired
     * @covers \App\Model\Attribute::__construct
     */
    public function testGetRequired()
    {
        $this->assertTrue($this->getInstance(['required' => 1])->isRequired());
        $this->assertFalse($this->getInstance([])->isRequired());
    }

    /**
     * @covers \App\Model\Attribute::getOptions
     * @covers \App\Model\Attribute::__construct
     */
    public function testGetOptions()
    {
        $attribute = new Attribute($this->typeFactory, ['options' => 'options']);
        $this->assertEquals('options', $attribute->getOptions());
        $attribute = new Attribute($this->typeFactory, ['options' => '']);
        $this->assertEquals('', $attribute->getOptions());
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
     * @covers \App\Model\Attribute::getAdminGrid
     * @covers \App\Model\Attribute::__construct
     */
    public function testGetAdminGrid()
    {
        $attribute = new Attribute($this->typeFactory, ['admin_grid' => '1']);
        $this->assertEquals(1, $attribute->getAdminGrid());
        $attribute = new Attribute($this->typeFactory, []);
        $this->assertEquals(0, $attribute->getAdminGrid());
    }

    /**
     * @covers \App\Model\Attribute::getAdminGridFilter
     * @covers \App\Model\Attribute::__construct
     */
    public function testGetAdminGridFilter()
    {
        $attribute = new Attribute($this->typeFactory, ['admin_grid' => '1', 'admin_grid_filter' => 1]);
        $this->assertTrue($attribute->getAdminGridFilter());
        $attribute = new Attribute($this->typeFactory, []);
        $this->assertFalse($attribute->getAdminGridFilter());
        $attribute = new Attribute($this->typeFactory, ['admin_grid' => 0, 'admin_grid_filter' => 1]);
        $this->assertFalse($attribute->getAdminGridFilter());
        $attribute = new Attribute($this->typeFactory, ['admin_grid' => 1, 'admin_grid_filter' => 0]);
        $this->assertFalse($attribute->getAdminGridFilter());
        $attribute = new Attribute($this->typeFactory, ['admin_grid' => 0, 'admin_grid_filter' => 0]);
        $this->assertFalse($attribute->getAdminGridFilter());
    }

    /**
     * @covers \App\Model\Attribute::getDefaultValue
     * @covers \App\Model\Attribute::__construct
     */
    public function testGetDefaultValue()
    {
        $attribute = new Attribute($this->typeFactory, ['default_value' => 'default']);
        $this->assertEquals('default', $attribute->getDefaultValue());
        $attribute = new Attribute($this->typeFactory, ['default_value' => '']);
        $this->assertEquals('', $attribute->getDefaultValue());
    }

    /**
     * @covers \App\Model\Attribute::getShowInList
     * @covers \App\Model\Attribute::__construct
     */
    public function testGetShowInList()
    {
        /** @var Entity | MockObject $entity1 */
        $entity1 = $this->createMock(Entity::class);
        $entity1->method('getFrontendList')->willReturn(true);

        /** @var Entity | MockObject $entity2 */
        $entity2 = $this->createMock(Entity::class);
        $entity2->method('getFrontendList')->willReturn(false);

        $attribute = new Attribute($this->typeFactory, ['show_in_list' => 1]);
        $attribute->setEntity($entity1);
        $this->assertTrue($attribute->getShowInList());
        $attribute->setEntity($entity2);
        $this->assertFalse($attribute->getShowInList());

        $attribute = new Attribute($this->typeFactory, ['show_in_list' => 0]);
        $attribute->setEntity($entity1);
        $this->assertFalse($attribute->getShowInList());
        $attribute->setEntity($entity2);
        $this->assertFalse($attribute->getShowInList());
    }

    /**
     * @covers \App\Model\Attribute::getShowInView
     * @covers \App\Model\Attribute::__construct
     */
    public function testGetShowInView()
    {
        /** @var Entity | MockObject $entity1 */
        $entity1 = $this->createMock(Entity::class);
        $entity1->method('getFrontendView')->willReturn(true);

        /** @var Entity | MockObject $entity2 */
        $entity2 = $this->createMock(Entity::class);
        $entity2->method('getFrontendView')->willReturn(false);

        $attribute = new Attribute($this->typeFactory, ['show_in_view' => 1]);
        $attribute->setEntity($entity1);
        $this->assertTrue($attribute->getShowInView());
        $attribute->setEntity($entity2);
        $this->assertFalse($attribute->getShowInView());

        $attribute = new Attribute($this->typeFactory, ['show_in_view' => 0]);
        $attribute->setEntity($entity1);
        $this->assertFalse($attribute->getShowInView());
        $attribute->setEntity($entity2);
        $this->assertFalse($attribute->getShowInView());
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
            $data
        );
    }
}
