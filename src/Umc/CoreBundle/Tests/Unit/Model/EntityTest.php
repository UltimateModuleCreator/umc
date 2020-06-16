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

namespace App\Umc\CoreBundle\Tests\Unit\Model;

use App\Umc\CoreBundle\Model\Attribute;
use App\Umc\CoreBundle\Model\Entity;
use App\Umc\CoreBundle\Model\Module;
use App\Umc\CoreBundle\Util\StringUtil;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class EntityTest extends TestCase
{
    /**
     * @var StringUtil | MockObject
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
     * setup tests
     */
    protected function setUp(): void
    {
        $this->stringUtil = $this->createMock(StringUtil::class);
        $this->attributeFactory = $this->createMock(Attribute\Factory::class);
        $this->module = $this->createMock(Module::class);
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Entity::toArray()
     * @covers \App\Umc\CoreBundle\Model\Entity::__construct()
     */
    public function testToArray()
    {
        $attribute = $this->createMock(Attribute::class);
        $attribute->expects($this->once())->method('toArray');
        $this->attributeFactory->expects($this->once())->method('create')->willReturn($attribute);
        $result = $this->getInstance(['_attributes' => [[]]])->toArray();
        $this->assertArrayHasKey('name_singular', $result);
        $this->assertArrayHasKey('label_plural', $result);
        $this->assertArrayHasKey('_attributes', $result);
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Entity::getNameAttribute
     * @covers \App\Umc\CoreBundle\Model\Entity::__construct()
     */
    public function testGetNameAttribute()
    {
        /** @var Attribute | MockObject $attribute1 */
        $attribute1 = $this->createMock(Attribute::class);
        /** @var Attribute | MockObject $attribute2 */
        $attribute2 = $this->createMock(Attribute::class);
        $attribute2->method('isName')->willReturn(true);
        $this->attributeFactory->expects($this->exactly(2))->method('create')
            ->willReturnOnConsecutiveCalls($attribute1, $attribute2);
        $entity = $this->getInstance(['_attributes' => [['data'], ['data']]]);
        $this->assertEquals($attribute2, $entity->getNameAttribute());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Entity::getNameAttribute
     * @covers \App\Umc\CoreBundle\Model\Entity::__construct
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
     * @covers \App\Umc\CoreBundle\Model\Entity::getAttributes
     * @covers \App\Umc\CoreBundle\Model\Entity::__construct
     */
    public function testGetAttributes()
    {
        /** @var Attribute | MockObject $attribute1 */
        $attribute1 = $this->createMock(Attribute::class);
        /** @var Attribute | MockObject $attribute2 */
        $attribute2 = $this->createMock(Attribute::class);
        $this->attributeFactory->expects($this->exactly(2))->method('create')
            ->willReturnOnConsecutiveCalls($attribute1, $attribute2);
        $entity = $this->getInstance(['_attributes' => [['data'], ['data']]]);
        $this->assertEquals([$attribute1, $attribute2], $entity->getAttributes());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Entity::getModule
     * @covers \App\Umc\CoreBundle\Model\Entity::__construct
     */
    public function testGetModule()
    {
        $this->assertEquals($this->module, $this->getInstance([])->getModule());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Entity::getNameSingular
     * @covers \App\Umc\CoreBundle\Model\Entity::__construct
     */
    public function testGetNameSingular()
    {
        $this->assertEquals('name', $this->getInstance(['name_singular' => 'name'])->getNameSingular());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Entity::getNamePlural
     * @covers \App\Umc\CoreBundle\Model\Entity::__construct
     */
    public function testGetNamePlural()
    {
        $this->assertEquals('name', $this->getInstance(['name_plural' => 'name'])->getNamePlural());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Entity::getLabelSingular
     * @covers \App\Umc\CoreBundle\Model\Entity::__construct
     */
    public function testGetLabelSingular()
    {
        $this->assertEquals('name', $this->getInstance(['label_singular' => 'name'])->getLabelSingular());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Entity::getLabelPlural
     * @covers \App\Umc\CoreBundle\Model\Entity::__construct
     */
    public function testGetLabelPlural()
    {
        $this->assertEquals('name', $this->getInstance(['label_plural' => 'name'])->getLabelPlural());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Entity::isSearch
     * @covers \App\Umc\CoreBundle\Model\Entity::__construct
     */
    public function testIsSearch()
    {
        $this->assertTrue($this->getInstance(['search' => 1])->isSearch());
        $this->assertFalse($this->getInstance([])->isSearch());
        $this->assertFalse($this->getInstance(['search' => false])->isSearch());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Entity::isStore
     * @covers \App\Umc\CoreBundle\Model\Entity::__construct
     */
    public function testIsStore()
    {
        $this->assertTrue($this->getInstance(['store' => 1])->isStore());
        $this->assertFalse($this->getInstance([])->isStore());
        $this->assertFalse($this->getInstance(['store' => false])->isStore());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Entity::isFrontend
     * @covers \App\Umc\CoreBundle\Model\Entity::__construct
     */
    public function testIsFrontend()
    {
        $this->module->method('isFrontend')->willReturn(true);
        $this->assertTrue($this->getInstance(['frontend' => 1])->isFrontend());
        $this->assertFalse($this->getInstance([])->isFrontend());
        $this->assertFalse($this->getInstance(['frontend' => false])->isFrontend());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Entity::isFooterLinks
     * @covers \App\Umc\CoreBundle\Model\Entity::__construct
     */
    public function testIsFooterLinks()
    {
        $this->module->method('isFrontend')->willReturn(true);
        $this->assertTrue($this->getInstance(['footer_links' => 1, 'frontend' => 1])->isFooterLinks());
        $this->assertFalse($this->getInstance(['footer_links' => 1, 'frontend' => 0])->isFooterLinks());
        $this->assertFalse($this->getInstance(['footer_links' => 0, 'frontend' => 1])->isFooterLinks());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Entity::isFooterLinks
     * @covers \App\Umc\CoreBundle\Model\Entity::__construct
     */
    public function testIsFooterLinksNoFrontendForModule()
    {
        $this->module->method('isFrontend')->willReturn(false);
        $this->assertFalse($this->getInstance(['footer_links' => 1, 'frontend' => 1])->isFooterLinks());
        $this->assertFalse($this->getInstance(['footer_links' => 1, 'frontend' => 0])->isFooterLinks());
        $this->assertFalse($this->getInstance(['footer_links' => 0, 'frontend' => 1])->isFooterLinks());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Entity::isTopMenu
     * @covers \App\Umc\CoreBundle\Model\Entity::__construct
     */
    public function testIsTopMenu()
    {
        $this->module->method('isFrontend')->willReturn(true);
        $this->assertTrue($this->getInstance(['top_menu' => 1, 'frontend' => 1])->isTopMenu());
        $this->assertFalse($this->getInstance(['top_menu' => 1, 'frontend' => 0])->isTopMenu());
        $this->assertFalse($this->getInstance(['top_menu' => 0, 'frontend' => 1])->isTopMenu());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Entity::isTopMenu
     * @covers \App\Umc\CoreBundle\Model\Entity::__construct
     */
    public function testIsTopMenuNoFrontendForModule()
    {
        $this->module->method('isFrontend')->willReturn(false);
        $this->assertFalse($this->getInstance(['top_menu' => 1, 'frontend' => 1])->isTopMenu());
        $this->assertFalse($this->getInstance(['top_menu' => 1, 'frontend' => 0])->isTopMenu());
        $this->assertFalse($this->getInstance(['top_menu' => 0, 'frontend' => 1])->isTopMenu());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Entity::isFrontend
     * @covers \App\Umc\CoreBundle\Model\Entity::__construct
     */
    public function testIsFrontendWithModuleFrontendDisabled()
    {
        $this->module->method('isFrontend')->willReturn(false);
        $this->assertFalse($this->getInstance(['frontend' => 1])->isFrontend());
        $this->assertFalse($this->getInstance([])->isFrontend());
        $this->assertFalse($this->getInstance(['frontend' => false])->isFrontend());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Entity::isSeo
     * @covers \App\Umc\CoreBundle\Model\Entity::__construct
     */
    public function testIsSeo()
    {
        $this->module->method('isFrontend')->willReturn(true);
        $this->assertTrue($this->getInstance(['seo' => 1, 'frontend' => 1])->isSeo());
        $this->assertFalse($this->getInstance(['seo' => 1, 'frontend' => 0])->isSeo());
        $this->assertFalse($this->getInstance([])->isSeo());
        $this->assertFalse($this->getInstance(['seo' => false, 'frontend'])->isSeo());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Entity::getAttributesWithType
     * @covers \App\Umc\CoreBundle\Model\Entity::initAttributeCacheData
     * @covers \App\Umc\CoreBundle\Model\Entity::__construct
     */
    public function testGetAttributesWithType()
    {
        $attribute1 = $this->createMock(Attribute::class);
        $attribute1->expects($this->once())->method('getType')->willReturn('text');
        $attribute1->method('getFlags')->willReturn([]);
        $attribute2 = $this->createMock(Attribute::class);
        $attribute2->expects($this->once())->method('getType')->willReturn('textarea');
        $attribute2->method('getFlags')->willReturn([]);
        $this->attributeFactory->expects($this->exactly(2))->method('create')
            ->willReturnOnConsecutiveCalls($attribute1, $attribute2);
        $instance = $this->getInstance(['_attributes' => [[], []]]);
        $this->assertEquals([$attribute1], $instance->getAttributesWithType('text'));
        $this->assertEquals([$attribute2], $instance->getAttributesWithType('textarea'));
        $this->assertEquals([], $instance->getAttributesWithType('missing'));
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Entity::hasAttributesWithType
     * @covers \App\Umc\CoreBundle\Model\Entity::initAttributeCacheData
     * @covers \App\Umc\CoreBundle\Model\Entity::__construct
     */
    public function testHasAttributesWithType()
    {
        $attribute2 = $this->createMock(Attribute::class);
        $attribute2->expects($this->once())->method('getType')->willReturn('textarea');
        $attribute2->method('getFlags')->willReturn([]);
        $attribute1 = $this->createMock(Attribute::class);
        $attribute1->expects($this->once())->method('getType')->willReturn('text');
        $attribute1->method('getFlags')->willReturn([]);
        $this->attributeFactory->expects($this->exactly(2))->method('create')
            ->willReturnOnConsecutiveCalls($attribute1, $attribute2);
        $instance = $this->getInstance(['_attributes' => [[], []]]);
        $this->assertTrue($instance->hasAttributesWithType('text'));
        $this->assertTrue($instance->hasAttributesWithType('textarea'));
        $this->assertFalse($instance->hasAttributesWithType('missing'));
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Entity::getFlags
     * @covers \App\Umc\CoreBundle\Model\Entity::initAttributeCacheData
     * @covers \App\Umc\CoreBundle\Model\Entity::cacheAttributeData
     * @covers \App\Umc\CoreBundle\Model\Entity::__construct
     */
    public function testGetFlags()
    {
        $entity = $this->getInstanceForFlags();
        $flags = $entity->getFlags();
        $this->assertTrue(in_array('search', $flags));
        $this->assertTrue(in_array('store', $flags));
        $this->assertTrue(in_array('attribute_flag1', $flags));
        $this->assertTrue(in_array('attribute_type_text', $flags));
        $this->assertTrue(in_array('attribute_flag22', $flags));
        $this->assertTrue(in_array('attribute_type_textarea', $flags));
        $this->assertFalse(in_array('top_menu', $flags));
        $this->assertFalse(in_array('attribute_type_dropdown', $flags));
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Entity::getAttributesWithFlag
     * @covers \App\Umc\CoreBundle\Model\Entity::initAttributeCacheData
     * @covers \App\Umc\CoreBundle\Model\Entity::cacheAttributeData
     * @covers \App\Umc\CoreBundle\Model\Entity::__construct
     */
    public function testGetAttributesWithFlag()
    {
        $entity = $this->getInstanceForFlags();
        $this->assertEquals(1, count($entity->getAttributesWithFlag('flag1')));
        $this->assertEquals(1, count($entity->getAttributesWithFlag('flag22')));
        $this->assertEquals(2, count($entity->getAttributesWithFlag('common')));
        $this->assertEquals(0, count($entity->getAttributesWithFlag('dummy')));
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Entity::hasAttributesWithFlag
     * @covers \App\Umc\CoreBundle\Model\Entity::initAttributeCacheData
     * @covers \App\Umc\CoreBundle\Model\Entity::cacheAttributeData
     * @covers \App\Umc\CoreBundle\Model\Entity::__construct
     */
    public function testHasAttributesWithFlag()
    {
        $entity = $this->getInstanceForFlags();
        $this->assertTrue($entity->hasAttributesWithFlag('flag1'));
        $this->assertTrue($entity->hasAttributesWithFlag('flag22'));
        $this->assertTrue($entity->hasAttributesWithFlag('common'));
        $this->assertFalse($entity->hasAttributesWithFlag('dummy'));
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Entity::getAttributesWithFlagPrefix
     * @covers \App\Umc\CoreBundle\Model\Entity::__construct
     */
    public function testGetAttributesWithFlagPrefix()
    {
        $attribute1 = $this->createMock(Attribute::class);
        $attribute1->method('getFlagSuffixes')->willReturnMap([
            ['prefix1_', ['suffix1', 'suffix2']],
            ['prefix2_', ['suffix3', 'suffix4']]
        ]);

        $attribute2 = $this->createMock(Attribute::class);
        $attribute2->method('getFlagSuffixes')->willReturnMap([
            ['prefix1_', ['suffix1', 'suffix3']],
            ['prefix2_', []]
        ]);
        $this->attributeFactory->method('create')
            ->willReturnOnConsecutiveCalls($attribute1, $attribute2);
        $entity = $this->getInstance(['_attributes' => [[], []]]);
        $this->assertEquals(3, count($entity->getAttributesWithFlagPrefix('prefix1_')));
        $this->assertEquals(2, count($entity->getAttributesWithFlagPrefix('prefix2_')));
    }

    /**
     * @return Entity
     */
    private function getInstanceForFlags()
    {
        $this->module->method('isFrontend')->willReturn(true);
        $attribute1 = $this->createMock(Attribute::class);
        $attribute1->expects($this->once())->method('getType')->willReturn('text');
        $attribute1->method('getFlags')->willReturn(['flag1', 'flag11', 'common']);

        $attribute2 = $this->createMock(Attribute::class);
        $attribute2->expects($this->once())->method('getType')->willReturn('textarea');
        $attribute2->method('getFlags')->willReturn(['flag2', 'flag22', 'common']);
        $this->attributeFactory->method('create')
            ->willReturnOnConsecutiveCalls($attribute1, $attribute2);
        return $this->getInstance([
            'search' => true,
            'store' => true,
            'frontend' => true,
            'top_menu' => false,
            '_attributes' => [[], []]
        ]);
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
