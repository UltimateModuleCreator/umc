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
use App\Util\Sorter;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class EntityTest extends TestCase
{
    /**
     * @var Sorter | MockObject
     */
    private $sorter;
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
        $this->sorter = $this->createMock(Sorter::class);
        $this->sorter->method('sort')->willReturnArgument(0);
        $this->entity = new Entity($this->sorter, $this->data);
    }

    /**
     * @covers \App\Model\Entity::getData()
     * @covers \App\Model\Entity::__construct()
     */
    public function testGetData()
    {
        $this->assertEquals('Entity', $this->entity->getData('name_singular'));
        $this->assertEquals('dummy', $this->entity->getData('dummy'));
        $this->assertNull($this->entity->getData('non_existent'));
        $this->assertEquals('default', $this->entity->getData('non_existent', 'default'));
    }

    /**
     * @covers \App\Model\Entity::getRawData()
     * @covers \App\Model\Entity::__construct()
     */
    public function testGetRawData()
    {
        $this->assertEquals($this->data, $this->entity->getRawData());
    }

    /**
     * @covers \App\Model\Entity::getPropertiesData()
     * @covers \App\Model\Entity::__construct()
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
     * @covers \App\Model\Entity::__construct()
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
     * @covers \App\Model\Entity::__construct()
     */
    public function testGetNameAttribute()
    {
        /** @var Attribute | MockObject $attribute1 */
        $attribute1 = $this->createMock(Attribute::class);
        /** @var Attribute | MockObject $attribute2 */
        $attribute2 = $this->createMock(Attribute::class);
        $attribute2->method('getIsName')->willReturn(true);
        $this->entity->addAttribute($attribute1);
        $this->entity->addAttribute($attribute2);
        $this->assertEquals($attribute2, $this->entity->getNameAttribute());
    }

    /**
     * @covers  \App\Model\Entity::getNameAttribute()
     * @covers \App\Model\Entity::__construct()
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
     * @covers \App\Model\Entity::__construct()
     */
    public function testHasAttributeType()
    {
        /** @var Attribute | MockObject $attribute1 */
        $attribute1 = $this->createMock(Attribute::class);
        $attribute1->method('getType')->willReturn('text');
        /** @var Attribute | MockObject $attribute2 */
        $attribute2 = $this->createMock(Attribute::class);
        $attribute2->method('getType')->willReturn('textarea');
        $entity = new Entity($this->sorter);
        $entity->addAttribute($attribute1);
        $entity->addAttribute($attribute2);
        $this->assertTrue($entity->hasAttributeType('text'));
        $this->assertTrue($entity->hasAttributeType('textarea'));
        $this->assertFalse($entity->hasAttributeType('dummy'));
    }


    /**
     * @covers \App\Model\Entity::getModule
     * @covers \App\Model\Entity::setModule
     * @covers \App\Model\Entity::__construct()
     */
    public function testGetModule()
    {
        /** @var Module | MockObject $module */
        $module = $this->createMock(Module::class);
        $entity = new Entity($this->sorter);
        $entity->setModule($module);
        $this->assertEquals($module, $entity->getModule());
    }

    /**
     * @covers \App\Model\Entity::getAttributes
     * @covers \App\Model\Entity::addAttribute
     * @covers \App\Model\Entity::__construct()
     */
    public function testAddAttribute()
    {
        /** @var Attribute | MockObject $attribute1 */
        $attribute1 = $this->createMock(Attribute::class);
        /** @var Attribute | MockObject $attribute2 */
        $attribute2 = $this->createMock(Attribute::class);
        $entity = new Entity($this->sorter);
        $entity->addAttribute($attribute1);
        $entity->addAttribute($attribute2);
        $attributes = $entity->getAttributes();
        $this->assertEquals(2, count($attributes));
        $this->assertEquals($attribute1, $attributes[0]);
        $this->assertEquals($attribute2, $attributes[1]);
    }

    /**
     * @covers \App\Model\Entity::getAttributesWithTypeConfig()
     * @covers \App\Model\Entity::getAttributesWithTypeConfigString()
     * @covers \App\Model\Entity::arrayToPrintString()
     * @covers \App\Model\Entity::getAttributeCodes()
     * @covers \App\Model\Entity::__construct()
     */
    public function testGetAttributesWithTypeConfig()
    {
        /** @var Attribute | MockObject $attribute1 */
        $attribute1 = $this->createMock(Attribute::class);
        $type1 = $this->createMock(Attribute\TypeInterface::class);
        $type1->method('getData')
            ->willReturnMap([
                ['multiple', null, false]
            ]);
        $attribute1->method('getTypeInstance')->willReturn($type1);

        $entity1 = new Entity($this->sorter);
        $entity1->addAttribute($attribute1);
        $this->assertEquals([], $entity1->getAttributesWithTypeConfig('multiple'));
        $this->assertEquals('', $entity1->getAttributesWithTypeConfigString('multiple'));

        /** @var Attribute | MockObject $attribute2 */
        $attribute2 = $this->createMock(Attribute::class);
        $attribute2->method('getCode')->willReturn('attr2');
        $type2 = $this->createMock(Attribute\TypeInterface::class);
        $type2->method('getData')->willReturnMap([
            ['multiple', null, true]
        ]);
        $attribute2->method('getTypeInstance')->willReturn($type2);

        $entity2 = new Entity($this->sorter);
        $entity2->addAttribute($attribute1);
        $entity2->addAttribute($attribute2);
        $this->assertEquals([$attribute2], $entity2->getAttributesWithTypeConfig('multiple'));
        $this->assertEquals("    'attr2'", $entity2->getAttributesWithTypeConfigString('multiple', 1));
    }

    /**
     * @covers \App\Model\Entity::getAttributesWithType()
     * @covers \App\Model\Entity::getAttributesWithTypeString()
     * @covers \App\Model\Entity::arrayToPrintString()
     * @covers \App\Model\Entity::getAttributeCodes()
     * @covers \App\Model\Entity::__construct()
     */
    public function testGetDateFields()
    {
        /** @var Attribute | MockObject $attribute1 */
        $attribute1 = $this->createMock(Attribute::class);
        $attribute1->method('getType')->willReturn('select');

        $entity1 = new Entity($this->sorter);
        $entity1->addAttribute($attribute1);
        $this->assertEquals([], $entity1->getAttributesWithType('date'));
        $this->assertEquals('', $entity1->getAttributesWithTypeString('date'));

        /** @var Attribute | MockObject $attribute2 */
        $attribute2 = $this->createMock(Attribute::class);
        $attribute2->method('getCode')->willReturn('attr2');
        $attribute2->method('getType')->willReturn('date');
        $entity2 = new Entity($this->sorter);
        $entity2->addAttribute($attribute1);
        $entity2->addAttribute($attribute2);
        $this->assertEquals([$attribute2], $entity2->getAttributesWithType('date'));
        $this->assertEquals("                'attr2'", $entity2->getAttributesWithTypeString('date', 4));
    }

    /**
     * @covers \App\Model\Entity::getNameSingular
     * @covers \App\Model\Entity::__construct
     */
    public function testGetNameSingular()
    {
        $entity = new Entity($this->sorter, ['name_singular' => 'name']);
        $this->assertEquals('name', $entity->getNameSingular());
        $entity = new Entity($this->sorter, ['name_singular' => '']);
        $this->assertEquals('', $entity->getNameSingular());
    }

    /**
     * @covers \App\Model\Entity::getNamePlural
     * @covers \App\Model\Entity::__construct
     */
    public function testGetNamePlural()
    {
        $entity = new Entity($this->sorter, ['name_plural' => 'name']);
        $this->assertEquals('name', $entity->getNamePlural());
        $entity = new Entity($this->sorter, ['name_plural' => '']);
        $this->assertEquals('', $entity->getNamePlural());
    }

    /**
     * @covers \App\Model\Entity::getLabelSingular
     * @covers \App\Model\Entity::__construct
     */
    public function testGetLabelSingular()
    {
        $entity = new Entity($this->sorter, ['label_singular' => 'name']);
        $this->assertEquals('name', $entity->getLabelSingular());
        $entity = new Entity($this->sorter, ['label_singular' => '']);
        $this->assertEquals('', $entity->getLabelSingular());
    }

    /**
     * @covers \App\Model\Entity::getLabelPlural
     * @covers \App\Model\Entity::__construct
     */
    public function testGetLabelPlural()
    {
        $entity = new Entity($this->sorter, ['label_plural' => 'name']);
        $this->assertEquals('name', $entity->getLabelPlural());
        $entity = new Entity($this->sorter, ['label_plural' => '']);
        $this->assertEquals('', $entity->getLabelPlural());
    }

    /**
     * @covers \App\Model\Entity::getAddCreatedToGrid
     * @covers \App\Model\Entity::__construct
     */
    public function testGetAddCreatedToGrid()
    {
        $entity = new Entity($this->sorter, ['add_created_to_grid' => '1']);
        $this->assertTrue($entity->getAddCreatedToGrid());
        $entity = new Entity($this->sorter, []);
        $this->assertFalse($entity->getAddCreatedToGrid());
    }

    /**
     * @covers \App\Model\Entity::getAddUpdatedToGrid
     * @covers \App\Model\Entity::__construct
     */
    public function testGetAddUpdatedToGrid()
    {
        $entity = new Entity($this->sorter, ['add_updated_to_grid' => '1']);
        $this->assertTrue($entity->getAddUpdatedToGrid());
        $entity = new Entity($this->sorter, []);
        $this->assertFalse($entity->getAddUpdatedToGrid());
    }

    /**
     * @covers \App\Model\Entity::getSearch
     * @covers \App\Model\Entity::__construct
     */
    public function testGetSearch()
    {
        $entity = new Entity($this->sorter, ['search' => '1']);
        $this->assertTrue($entity->getSearch());
        $entity = new Entity($this->sorter, []);
        $this->assertFalse($entity->getSearch());
    }

    /**
     * @covers \App\Model\Entity::getStore
     * @covers \App\Model\Entity::__construct
     */
    public function testGetStore()
    {
        $entity = new Entity($this->sorter, ['store' => '1']);
        $this->assertTrue($entity->getStore());
        $entity = new Entity($this->sorter, []);
        $this->assertFalse($entity->getStore());
    }

    /**
     * @covers \App\Model\Entity::getFrontendList
     * @covers \App\Model\Entity::__construct
     */
    public function testGeFrontendList()
    {
        $entity = new Entity($this->sorter, ['frontend_list' => '1']);
        $this->assertTrue($entity->getFrontendList());
        $entity = new Entity($this->sorter, []);
        $this->assertFalse($entity->getFrontendList());
    }

    /**
     * @covers \App\Model\Entity::getFrontendView
     * @covers \App\Model\Entity::__construct
     */
    public function testGeFrontendView()
    {
        $entity = new Entity($this->sorter, ['frontend_view' => '1']);
        $this->assertTrue($entity->getFrontendView());
        $entity = new Entity($this->sorter, []);
        $this->assertFalse($entity->getFrontendView());
    }

    /**
     * @covers \App\Model\Entity::getSeo
     * @covers \App\Model\Entity::__construct
     */
    public function testGetSeo()
    {
        $entity = new Entity($this->sorter, ['seo' => '1']);
        $this->assertTrue($entity->getSeo());
        $entity = new Entity($this->sorter, []);
        $this->assertFalse($entity->getSeo());
    }

    /**
     * @covers \App\Model\Entity::getPosition
     * @covers \App\Model\Entity::__construct
     */
    public function testGetPosition()
    {
        $entity = new Entity($this->sorter, ['position' => '10']);
        $this->assertEquals(10, $entity->getPosition());
        $entity = new Entity($this->sorter, []);
        $this->assertEquals(0, $entity->getPosition());
    }

    /**
     * @covers \App\Model\Entity::getMenuLink
     * @covers \App\Model\Entity::__construct
     */
    public function testGetMenuLink()
    {
        $entity = new Entity($this->sorter, []);
        $this->assertEquals(0, $entity->getMenuLink());
        $entity = new Entity($this->sorter, ['menu_link' => 1, 'frontend_list' => 1]);
        $this->assertEquals(1, $entity->getMenuLink());
        $entity = new Entity($this->sorter, ['menu_link' => 2, 'frontend_list' => 1]);
        $this->assertEquals(2, $entity->getMenuLink());
        $entity = new Entity($this->sorter, ['menu_link' => 3, 'frontend_list' => 1]);
        $this->assertEquals(0, $entity->getMenuLink());
        $entity = new Entity($this->sorter, ['menu_link' => 1, 'frontend_list' => 0]);
        $this->assertEquals(0, $entity->getMenuLink());
    }

    /**
     * @covers \App\Model\Entity::hasFrontend
     * @covers \App\Model\Entity::__construct
     */
    public function testHasFrontend()
    {
        $entity = new Entity($this->sorter, []);
        $this->assertFalse($entity->hasFrontend());
        $entity = new Entity($this->sorter, ['frontend_list' => 1]);
        $this->assertTrue($entity->hasFrontend());
        $entity = new Entity($this->sorter, ['frontend_view' => 1]);
        $this->assertTrue($entity->hasFrontend());
        $entity = new Entity($this->sorter, ['frontend_list' => 1, 'frontend_view' => 1]);
        $this->assertTrue($entity->hasFrontend());
        $entity = new Entity($this->sorter, ['frontend_list' => 0, 'frontend_view' => 0]);
        $this->assertFalse($entity->hasFrontend());
    }

    /**
     * @covers \App\Model\Entity::getIsTree
     * @covers \App\Model\Entity::__construct
     */
    public function testGetIsTree()
    {
        $entity = new Entity($this->sorter, ['is_tree' => '1']);
        $this->assertTrue($entity->getIsTree());
        $entity = new Entity($this->sorter, []);
        $this->assertFalse($entity->getIsTree());
    }
}
