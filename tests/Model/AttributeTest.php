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
use App\Model\Entity;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class AttributeTest extends TestCase
{
    /**
     * @var Attribute
     */
    private $attribute;
    /**
     * @var Attribute\TypeFactory
     */
    private $typeFactory;
    /**
     * @var array
     */
    private $data = [
        'label' => 'Attribute',
        'code' => 'attribute',
        'dummy' => 'dummy'
    ];

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->typeFactory = $this->createMock(Attribute\TypeFactory::class);
        $this->attribute = new Attribute($this->typeFactory, $this->data);
    }

    /**
     * @covers \App\Model\Attribute::getData()
     */
    public function testGetData()
    {
        $this->assertEquals('Attribute', $this->attribute->getData('label'));
        $this->assertEquals('attribute', $this->attribute->getData('code'));
        $this->assertNull($this->attribute->getData('non_existent'));
        $this->assertEquals('default', $this->attribute->getData('non_existent', 'default'));
    }

    /**
     * @covers \App\Model\Attribute::getRawData()
     */
    public function testGetRawData()
    {
        $this->assertEquals($this->data, $this->attribute->getRawData());
    }

    /**
     * @covers \App\Model\Attribute::getPropertiesData()
     */
    public function testGetPropertiesData()
    {
        $propertiesData = $this->attribute->getPropertiesData();
        $this->assertArrayHasKey('label', $propertiesData);
        $this->assertArrayHasKey('code', $propertiesData);
        $this->assertArrayNotHasKey('dummy', $propertiesData);
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
        $typeInstance = $this->createMock(Attribute\TypeInterface::class);
        $this->typeFactory->expects($this->once())->method('create')->willReturn($typeInstance);
        //test memoizing
        $attrTypeInstance = $this->attribute->getTypeInstance();
        $this->attribute->getTypeInstance();
        $this->assertEquals($typeInstance, $attrTypeInstance);
    }

    /**
     * @covers \App\Model\Attribute::getEntity
     * @covers \App\Model\Attribute::setEntity
     */
    public function testSetEntity()
    {
        /** @var Entity | MockObject $entity */
        $entity = $this->createMock(Entity::class);
        $this->attribute->setEntity($entity);
        $this->assertEquals($entity, $this->attribute->getEntity());
    }

    /**
     * @covers \App\Model\Attribute::getProcessedOptions
     * @covers \App\Model\Attribute::toConstantName
     */
    public function testGetProcessedOptions()
    {
        $options = "o1\no2\n3\n";
        $attribute = new Attribute($this->typeFactory, ['options' => $options]);
        $expected = [
            'O1' => [
                'value' => 1,
                'label' => 'o1'
            ],
            'O2' => [
                'value' => 2,
                'label' => 'o2'
            ],
            '_3' => [
                'value' => 3,
                'label' => '3'
            ],
            '_EMPTY' => [
                'value' => 4,
                'label' => ''
            ]
        ];
        $this->assertEquals($expected, $attribute->getProcessedOptions());
    }

    /**
     * @covers \App\Model\Attribute::getCode
     * @covers \App\Model\Attribute::__construct
     */
    public function testGetCode()
    {
        $attribute = new Attribute($this->typeFactory, ['code' => 'code']);
        $this->assertEquals('code', $attribute->getCode());
        $attribute = new Attribute($this->typeFactory, ['code' => '']);
        $this->assertEquals('', $attribute->getCode());
    }

    /**
     * @covers \App\Model\Attribute::getLabel
     * @covers \App\Model\Attribute::__construct
     */
    public function testGetLabel()
    {
        $attribute = new Attribute($this->typeFactory, ['label' => 'label']);
        $this->assertEquals('label', $attribute->getLabel());
        $attribute = new Attribute($this->typeFactory, ['label' => '']);
        $this->assertEquals('', $attribute->getLabel());
    }

    /**
     * @covers \App\Model\Attribute::getType
     * @covers \App\Model\Attribute::__construct
     */
    public function testGetType()
    {
        $attribute = new Attribute($this->typeFactory, ['type' => 'text']);
        $this->assertEquals('text', $attribute->getType());
        $attribute = new Attribute($this->typeFactory, ['type' => '']);
        $this->assertEquals('', $attribute->getType());
    }

    /**
     * @covers \App\Model\Attribute::getIsName
     * @covers \App\Model\Attribute::__construct
     */
    public function testGetIsName()
    {
        $attribute = new Attribute($this->typeFactory, ['is_name' => '1']);
        $this->assertTrue($attribute->getIsName());
        $attribute = new Attribute($this->typeFactory, []);
        $this->assertFalse($attribute->getIsName());
    }

    /**
     * @covers \App\Model\Attribute::getRequired
     * @covers \App\Model\Attribute::__construct
     */
    public function testGetRequired()
    {
        $attribute = new Attribute($this->typeFactory, ['required' => '1']);
        $this->assertTrue($attribute->getRequired());
        $attribute = new Attribute($this->typeFactory, []);
        $this->assertFalse($attribute->getRequired());
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
     * @covers \App\Model\Attribute::getPosition
     * @covers \App\Model\Attribute::__construct
     */
    public function testGetPosition()
    {
        $attribute = new Attribute($this->typeFactory, ['position' => '22']);
        $this->assertEquals(22, $attribute->getPosition());
        $attribute = new Attribute($this->typeFactory, []);
        $this->assertEquals(0, $attribute->getPosition());
    }

    /**
     * @covers \App\Model\Attribute::getNote
     * @covers \App\Model\Attribute::__construct
     */
    public function testGetNote()
    {
        $attribute = new Attribute($this->typeFactory, ['note' => 'note']);
        $this->assertEquals('note', $attribute->getNote());
        $attribute = new Attribute($this->typeFactory, ['note' => '']);
        $this->assertEquals('', $attribute->getNote());
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
}
