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
use App\Util\StringUtil;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class EntityTest extends TestCase
{
    /**
     * @var StringUtil
     */
    private $stringUtil;
    /**
     * @var Attribute\Factory | MockObject
     */
    private $attributeFactory;
    /**
     * @var Module | MockObject
     */
    private $module;
    /**
     * @var array
     * @deprecated
     */
    private $data = [
        'name_singular' => 'Entity',
        'name_plural' => 'Entities',
        'dummy' => 'dummy'
    ];

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->stringUtil = $this->createMock(StringUtil::class);
        $this->attributeFactory = $this->createMock(Attribute\Factory::class);
        $this->module = $this->createMock(Module::class);
    }

    /**
     * @covers \App\Model\Entity::toArray()
     * @covers \App\Model\Entity::__construct()
     */
    public function testToArray()
    {
        $attribute = $this->createMock(Entity::class);
        $attribute->expects($this->once())->method('toArray');
        $this->attributeFactory->expects($this->once())->method('create')->willReturn($attribute);
        $result = $this->getInstance(['_attributes' => [[]]])->toArray();
        $this->assertArrayHasKey('name_singular', $result);
        $this->assertArrayHasKey('label_plural', $result);
        $this->assertArrayHasKey('_attributes', $result);
    }

    /**
     * @covers \App\Model\Entity::getNameAttribute
     * @covers \App\Model\Entity::__construct()
     */
    public function testGetNameAttribute()
    {
        /** @var Attribute | MockObject $attribute1 */
        $attribute1 = $this->createMock(Attribute::class);
        /** @var Attribute | MockObject $attribute2 */
        $attribute2 = $this->createMock(Attribute::class);
        $attribute2->method('isName')->willReturn(true);
        $this->attributeFactory->expects($this->exactly(2))->willReturnOnConsecutiveCalls($attribute1, $attribute2);
        $entity = $this->getInstance(['_attributes' => [['data'], ['data']]]);
        $this->assertEquals($attribute2, $entity->getNameAttribute());
    }

    /**
     * @covers \App\Model\Entity::getNameAttribute
     * @covers \App\Model\Entity::__construct
     */
    public function testGetNameAttributeNoAttribute()
    {
        /** @var Attribute | MockObject $attribute1 */
        $attribute1 = $this->createMock(Attribute::class);
        /** @var Attribute | MockObject $attribute2 */
        $attribute2 = $this->createMock(Attribute::class);
        $this->attributeFactory->expects($this->exactly(2))->method('create')
            ->willReturnOnConsecutiveCalls($attribute1, $attribute2);
        $entity = $this->getInstance(['_attributes' => [['data'], ['data']]]);
        $this->assertNull($entity->getNameAttribute());
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
     * @covers \App\Model\Entity::__construct
     */
    public function testGetModule()
    {
        $this->assertEquals($this->module, $this->getInstance([])->getModule());
    }

    /**
     * @covers \App\Model\Entity::getNameSingular
     * @covers \App\Model\Entity::__construct
     */
    public function testGetNameSingular()
    {
        $this->assertEquals('name', $this->getInstance(['name_singular' => 'name'])->getNameSingular());
    }

    /**
     * @covers \App\Model\Entity::getNamePlural
     * @covers \App\Model\Entity::__construct
     */
    public function testGetNamePlural()
    {
        $this->assertEquals('name', $this->getInstance(['name_plural' => 'name'])->getNamePlural());
    }

    /**
     * @covers \App\Model\Entity::getLabelSingular
     * @covers \App\Model\Entity::__construct
     */
    public function testGetLabelSingular()
    {
        $this->assertEquals('name', $this->getInstance(['label_singular' => 'name'])->getLabelSingular());
    }

    /**
     * @covers \App\Model\Entity::getLabelPlural
     * @covers \App\Model\Entity::__construct
     */
    public function testGetLabelPlural()
    {
        $this->assertEquals('name', $this->getInstance(['label_plural' => 'name'])->getLabelPlural());
    }

    /**
     * @covers \App\Model\Entity::isSearch
     * @covers \App\Model\Entity::__construct
     */
    public function testIsSearch()
    {
        $this->assertTrue($this->getInstance(['search' => 1])->isSearch());
        $this->assertFalse($this->getInstance([])->isSearch());
        $this->assertFalse($this->getInstance(['search' => false])->isSearch());
    }

    /**
     * @covers \App\Model\Entity::isStore
     * @covers \App\Model\Entity::__construct
     */
    public function testIsStore()
    {
        $this->assertTrue($this->getInstance(['store' => 1])->isStore());
        $this->assertFalse($this->getInstance([])->isStore());
        $this->assertFalse($this->getInstance(['store' => false])->isStore());
    }

    /**
     * @covers \App\Model\Entity::isFrontendList
     * @covers \App\Model\Entity::__construct
     */
    public function testIsFrontendList()
    {
        $this->assertTrue($this->getInstance(['frontend_list' => 1])->isFrontendList());
        $this->assertFalse($this->getInstance([])->isFrontendList());
        $this->assertFalse($this->getInstance(['frontend_list' => false])->isFrontendList());
    }

    /**
     * @covers \App\Model\Entity::isFrontendView
     * @covers \App\Model\Entity::__construct
     */
    public function testIsFrontendView()
    {
        $this->assertTrue($this->getInstance(['frontend_view' => 1])->isFrontendView());
        $this->assertFalse($this->getInstance([])->isFrontendView());
        $this->assertFalse($this->getInstance(['frontend_view' => false])->isFrontendView());
    }

    /**
     * @covers \App\Model\Entity::isSeo
     * @covers \App\Model\Entity::__construct
     */
    public function testIsSeo()
    {
        $this->assertTrue($this->getInstance(['seo' => 1, 'frontend_view' => 1])->isSeo());
        $this->assertFalse($this->getInstance(['seo' => 1, 'frontend_view' => 0])->isSeo());
        $this->assertFalse($this->getInstance([])->isSeo());
        $this->assertFalse($this->getInstance(['seo' => false, 'frontend_view'])->isSeo());
    }

    /**
     * @covers \App\Model\Entity::getMenuLink
     * @covers \App\Model\Entity::__construct
     */
    public function testGetMenuLink()
    {
        $this->assertEquals(1, $this->getInstance(['menu_link' => 1, 'frontend_list' => 1])->getMenuLink());
        $this->assertEquals(0, $this->getInstance(['menu_link' => 2, 'frontend_list' => 0])->getMenuLink());
    }

    /**
     * @covers \App\Model\Entity::isFrontend
     * @covers \App\Model\Entity::__construct
     */
    public function testHasFrontend()
    {
        $entity = $this->getInstance([]);
        $this->assertFalse($entity->isFrontend());
        $entity = $this->getInstance(['frontend_list' => 1]);
        $this->assertTrue($entity->isFrontend());
        $entity = $this->getInstance(['frontend_view' => 1]);
        $this->assertTrue($entity->isFrontend());
        $entity = $this->getInstance(['frontend_list' => 1, 'frontend_view' => 1]);
        $this->assertTrue($entity->isFrontend());
        $entity = $this->getInstance(['frontend_list' => 0, 'frontend_view' => 0]);
        $this->assertFalse($entity->isFrontend());
    }

    /**
     * @param $data
     * @return Entity
     */
    private function getInstance($data): Entity
    {
        return new Entity(
            $this->stringUtil,
            $this->attributeFactory,
            $this->module,
            $data
        );
    }
}
