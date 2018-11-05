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
use App\Model\Module;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class EntityTest extends TestCase
{
    /**
     * @var Entity
     */
    private $entity;
    /**
     * @var array
     */
    private $data = [
        'name_singular' => 'Entity',
        'name_plural' => 'Entities',
        'dummy' => 'dummy'
    ];

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->entity = new Entity($this->data);
    }

    /**
     * @covers \App\Model\Entity::getData()
     */
    public function testGetData()
    {
        $this->assertEquals('Entity', $this->entity->getData('name_singular'));
        $this->assertEquals('dummy', $this->entity->getData('dummy'));
        $this->assertNull($this->entity->getData('non_existent'));
        $this->assertEquals('default', $this->entity->getData('non_existent', 'default'));
        $this->assertEquals("1", $this->entity->getData('type'));
    }

    /**
     * @covers \App\Model\Entity::getRawData()
     */
    public function testGetRawData()
    {
        $this->assertEquals($this->data, $this->entity->getRawData());
    }

    /**
     * @covers \App\Model\Entity::getPropertiesData()
     */
    public function testGetPropertiesData()
    {
        $propertiesData = $this->entity->getPropertiesData();
        $this->assertArrayHasKey('name_singular', $propertiesData);
        $this->assertArrayHasKey('label_singular', $propertiesData);
        $this->assertArrayNotHasKey('dummy', $propertiesData);
    }

    /**
     * @covers \App\Model\Entity::toArray()
     * @covers \App\Model\Entity::getAdditionalToArray()
     */
    public function testToArray()
    {
        /** @var Attribute | MockObject $attribute */
        $attribute = $this->createMock(Attribute::class);
        $attribute->expects($this->once())->method('toArray')->willReturn(['attribute']);
        $this->entity->addAttribute($attribute);
        $entityArray = $this->entity->toArray();
        $this->assertArrayHasKey('_attributes', $entityArray);
        $this->assertArrayHasKey('label_singular', $entityArray);
        $this->assertArrayNotHasKey('dummy', $entityArray);
    }

    /**
     * @covers  \App\Model\Entity::getNameAttribute()
     */
    public function testGetNameAttribute()
    {
        /** @var Attribute | MockObject $attribute1 */
        $attribute1 = $this->createMock(Attribute::class);
        /** @var Attribute | MockObject $attribute2 */
        $attribute2 = $this->createMock(Attribute::class);
        $attribute2->method('getData')->with('is_name')->willReturn(true);
        $this->entity->addAttribute($attribute1);
        $this->entity->addAttribute($attribute2);
        $this->assertEquals($attribute2, $this->entity->getNameAttribute());
    }

    /**
     * @covers  \App\Model\Entity::getNameAttribute()
     */
    public function testGetNameAttributeNoAttribute()
    {
        /** @var Attribute | MockObject $attribute1 */
        $attribute1 = $this->createMock(Attribute::class);
        /** @var Attribute | MockObject $attribute2 */
        $attribute2 = $this->createMock(Attribute::class);
        $this->entity->addAttribute($attribute1);
        $this->entity->addAttribute($attribute2);
        $this->assertNUll($this->entity->getNameAttribute());
    }

    /**
     * @covers \App\Model\Entity::hasAttributeType()
     */
    public function testHasAttributeType()
    {
        /** @var Attribute | MockObject $attribute1 */
        $attribute1 = $this->createMock(Attribute::class);
        $attribute1->method('getData')->with('type')->willReturn('text');
        /** @var Attribute | MockObject $attribute2 */
        $attribute2 = $this->createMock(Attribute::class);
        $attribute2->method('getData')->with('type')->willReturn('textarea');
        $entity = new Entity();
        $entity->addAttribute($attribute1);
        $entity->addAttribute($attribute2);
        $this->assertTrue($entity->hasAttributeType('text'));
        $this->assertTrue($entity->hasAttributeType('textarea'));
        $this->assertFalse($entity->hasAttributeType('dummy'));
    }

    /**
     * @covers \App\Model\Entity::hasFullTextAttributes()
     */
    public function testHasFullTextAttributes()
    {
        /** @var Attribute | MockObject $attribute1 */
        $attribute1 = $this->createMock(Attribute::class);
        $type1 = $this->createMock(Attribute\TypeInterface::class);
        $type1->method('getData')->with('full_text')->willReturn(false);
        $attribute1->method('getTypeInstance')->willReturn($type1);

        $entity1 = new Entity();
        $entity1->addAttribute($attribute1);
        $this->assertFalse($entity1->hasFullTextAttributes());

        /** @var Attribute | MockObject $attribute2 */
        $attribute2 = $this->createMock(Attribute::class);
        $type2 = $this->createMock(Attribute\TypeInterface::class);
        $type2->method('getData')->with('full_text')->willReturn(true);
        $attribute2->method('getTypeInstance')->willReturn($type2);

        $entity2 = new Entity();
        $entity2->addAttribute($attribute1);
        $entity2->addAttribute($attribute2);
        $this->assertTrue($entity2->hasFullTextAttributes());
    }

    /**
     * @covers \App\Model\Entity::getFullTextAttributes()
     */
    public function testGetFullTextAttributes()
    {
        /** @var Attribute | MockObject $attribute1 */
        $attribute1 = $this->createMock(Attribute::class);
        $type1 = $this->createMock(Attribute\TypeInterface::class);
        $type1->method('getData')->with('full_text')->willReturn(false);
        $attribute1->method('getTypeInstance')->willReturn($type1);

        $entity1 = new Entity();
        $entity1->addAttribute($attribute1);
        $this->assertEquals([], $entity1->getFullTextAttributes());

        /** @var Attribute | MockObject $attribute2 */
        $attribute2 = $this->createMock(Attribute::class);
        $type2 = $this->createMock(Attribute\TypeInterface::class);
        $type2->method('getData')->with('full_text')->willReturn(true);
        $attribute2->method('getTypeInstance')->willReturn($type2);

        $entity2 = new Entity();
        $entity2->addAttribute($attribute1);
        $entity2->addAttribute($attribute2);
        $this->assertEquals([$attribute2], $entity2->getFullTextAttributes());
    }

    /**
     * @covers \App\Model\Entity::getFullTextAttributeString()
     * @covers \App\Model\Entity::arrayToPrintString()
     */
    public function testGetFullTextAttributesString()
    {
        /** @var Attribute | MockObject $attribute1 */
        $attribute1 = $this->createMock(Attribute::class);
        $type1 = $this->createMock(Attribute\TypeInterface::class);
        $type1->method('getData')->with('full_text')->willReturn(false);
        $attribute1->method('getTypeInstance')->willReturn($type1);
        $attribute1->method('getData')->with('code')->willReturn('attr1');

        $entity1 = new Entity();
        $entity1->addAttribute($attribute1);
        $this->assertEquals('', $entity1->getFullTextAttributeString());

        /** @var Attribute | MockObject $attribute2 */
        $attribute2 = $this->createMock(Attribute::class);
        $type2 = $this->createMock(Attribute\TypeInterface::class);
        $type2->method('getData')->with('full_text')->willReturn(true);
        $attribute2->method('getTypeInstance')->willReturn($type2);
        $attribute2->method('getData')->with('code')->willReturn('attr2');

        /** @var Attribute | MockObject $attribute3 */
        $attribute3 = $this->createMock(Attribute::class);
        $type3 = $this->createMock(Attribute\TypeInterface::class);
        $type3->method('getData')->with('full_text')->willReturn(true);
        $attribute3->method('getTypeInstance')->willReturn($type3);
        $attribute3->method('getData')->with('code')->willReturn('attr3');

        $entity2 = new Entity();
        $entity2->addAttribute($attribute1);
        $entity2->addAttribute($attribute2);
        $entity2->addAttribute($attribute3);
        $this->assertEquals("    'attr2'," . PHP_EOL . "    'attr3'", $entity2->getFullTextAttributeString(1));
    }

    /**
     * @covers \App\Model\Entity::getModule
     * @covers \App\Model\Entity::setModule
     */
    public function testModule()
    {
        /** @var Module | MockObject $module */
        $module = $this->createMock(Module::class);
        $entity = new Entity();
        $entity->setModule($module);
        $this->assertEquals($module, $entity->getModule());
    }

    /**
     * @covers \App\Model\Entity::getAttributes
     * @covers \App\Model\Entity::addAttribute
     */
    public function testAddAttribute()
    {
        /** @var Attribute | MockObject $attribute1 */
        $attribute1 = $this->createMock(Attribute::class);
        /** @var Attribute | MockObject $attribute2 */
        $attribute2 = $this->createMock(Attribute::class);
        $entity = new Entity();
        $entity->addAttribute($attribute1);
        $entity->addAttribute($attribute2);
        $attributes = $entity->getAttributes();
        $this->assertEquals(2, count($attributes));
        $this->assertEquals($attribute1, $attributes[0]);
        $this->assertEquals($attribute2, $attributes[1]);
    }

    /**
     * @covers \App\Model\Entity::getMultiSelectFields()
     * @covers \App\Model\Entity::hasMultiSelectFields()
     * @covers \App\Model\Entity::getMultiSelectFieldsString()
     * @covers \App\Model\Entity::arrayToPrintString()
     */
    public function testGetMultiSelectFields()
    {
        /** @var Attribute | MockObject $attribute1 */
        $attribute1 = $this->createMock(Attribute::class);
        $type1 = $this->createMock(Attribute\TypeInterface::class);
        $type1->method('getData')->with('multiple')->willReturn(false);
        $attribute1->method('getTypeInstance')->willReturn($type1);

        $entity1 = new Entity();
        $entity1->addAttribute($attribute1);
        $this->assertEquals([], $entity1->getMultiSelectFields());
        $this->assertFalse($entity1->hasMultiSelectFields());
        $this->assertEquals('', $entity1->getMultiSelectFieldsString());

        /** @var Attribute | MockObject $attribute2 */
        $attribute2 = $this->createMock(Attribute::class);
        $attribute2->method('getData')->with('code')->willReturn('attr2');
        $type2 = $this->createMock(Attribute\TypeInterface::class);
        $type2->method('getData')->with('multiple')->willReturn(true);
        $attribute2->method('getTypeInstance')->willReturn($type2);

        $entity2 = new Entity();
        $entity2->addAttribute($attribute1);
        $entity2->addAttribute($attribute2);
        $this->assertEquals([$attribute2], $entity2->getMultiSelectFields());
        $this->assertTrue($entity2->hasMultiSelectFields());
        $this->assertEquals("            'attr2'", $entity2->getMultiSelectFieldsString());
    }

    /**
     * @covers \App\Model\Entity::getDateFields()
     * @covers \App\Model\Entity::getDateFieldsString()
     * @covers \App\Model\Entity::arrayToPrintString()
     */
    public function testGetDateFields()
    {
        /** @var Attribute | MockObject $attribute1 */
        $attribute1 = $this->createMock(Attribute::class);
        $type1 = $this->createMock(Attribute\TypeInterface::class);
        $type1->method('getData')->with('date')->willReturn('select');

        $entity1 = new Entity();
        $entity1->addAttribute($attribute1);
        $this->assertEquals([], $entity1->getDateFields());
        $this->assertEquals('', $entity1->getDateFieldsString());

        /** @var Attribute | MockObject $attribute2 */
        $attribute2 = $this->createMock(Attribute::class);
        $attribute2->method('getData')->willReturnMap([
            [
                'code',
                null,
                'attr2'
            ],
            [
                'type',
                null,
                'date'
            ],
        ]);
        $entity2 = new Entity();
        $entity2->addAttribute($attribute1);
        $entity2->addAttribute($attribute2);
        $this->assertEquals([$attribute2], $entity2->getDateFields());
        $this->assertEquals("                'attr2'", $entity2->getDateFieldsString());
    }
}
