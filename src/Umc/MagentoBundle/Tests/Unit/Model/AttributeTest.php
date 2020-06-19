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

namespace App\Umc\MagentoBundle\Tests\Unit\Model;

use App\Umc\CoreBundle\Model\Attribute\Dynamic\Factory as DynamicFactory;
use App\Umc\CoreBundle\Model\Attribute\Option;
use App\Umc\CoreBundle\Model\Attribute\Option\Factory as OptionFactory;
use App\Umc\CoreBundle\Model\Attribute\Type\Factory as TypeFactory;
use App\Umc\CoreBundle\Model\Entity;
use App\Umc\CoreBundle\Util\StringUtil;
use App\Umc\MagentoBundle\Model\Attribute;
use App\Umc\MagentoBundle\Model\Module;
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
     * @var Attribute\Type\BaseType | MockObject
     */
    private $typeInstance;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->typeInstance = $this->createMock(Attribute\Type\BaseType::class);
        $this->typeFactory = $this->createMock(TypeFactory::class);
        $this->typeFactory->method('create')->willReturn($this->typeInstance);
        $this->optionFactory = $this->createMock(OptionFactory::class);
        $this->dynamicFactory = $this->createMock(DynamicFactory::class);
        $this->stringUtil = $this->createMock(StringUtil::class);
        $this->entity = $this->createMock(Entity::class);
    }

    /**
     * @covers \App\Umc\MagentoBundle\Model\Attribute::isFullText
     * @covers \App\Umc\MagentoBundle\Model\Attribute::__construct
     */
    public function testIsFullText()
    {
        $this->typeInstance->method('hasFlag')->with('full_text')->willReturn(true);
        $this->assertTrue($this->getInstance(['full_text' => true])->isFullText());
        $this->assertFalse($this->getInstance(['full_text' => false])->isFullText());
    }

    /**
     * @covers \App\Umc\MagentoBundle\Model\Attribute::isFullText
     * @covers \App\Umc\MagentoBundle\Model\Attribute::__construct
     */
    public function testIsFullTextNotAllowed()
    {
        $this->typeInstance->method('hasFlag')->with('full_text')->willReturn(false);
        $this->assertFalse($this->getInstance(['full_text' => true])->isFullText());
        $this->assertFalse($this->getInstance(['full_text' => false])->isFullText());
    }

    /**
     * @covers \App\Umc\MagentoBundle\Model\Attribute::isExpanded
     * @covers \App\Umc\MagentoBundle\Model\Attribute::__construct
     */
    public function testIsExpanded()
    {
        $this->assertTrue($this->getInstance(['expanded' => true])->isExpanded());
        $this->assertFalse($this->getInstance(['expanded' => false])->isExpanded());
    }

    /**
     * @covers \App\Umc\MagentoBundle\Model\Attribute::getOptionSourceVirtualType
     * @covers \App\Umc\MagentoBundle\Model\Attribute::__construct
     */
    public function testGetOptionSourceVirtualType()
    {
        $module = $this->createMock(Module::class);
        $this->entity->method('getNameSingular')->willReturn('Name');
        $module->expects($this->once())->method('getModuleName')->willReturn('Md');
        $this->entity->method('getModule')->willReturn($module);
        $expected = ['Md', 'Name', 'Source', 'code'];
        $this->stringUtil->method('glueClassParts')->with($expected, '')->willReturn('type');
        $this->assertEquals('type', $this->getInstance(['code' => 'code'])->getOptionSourceVirtualType());
    }

    /**
     * @covers \App\Umc\MagentoBundle\Model\Attribute::toArray
     * @covers \App\Umc\MagentoBundle\Model\Attribute::__construct
     */
    public function testToArray()
    {
        $result = $this->getInstance([])->toArray();
        $this->assertArrayHasKey('full_text', $result);
        $this->assertArrayHasKey('expanded', $result);
    }

    /**
     * @covers \App\Umc\MagentoBundle\Model\Attribute::getFlags
     * @covers \App\Umc\MagentoBundle\Model\Attribute::__construct
     */
    public function testGetFlags()
    {
        $this->typeInstance->method('hasFlag')->with('full_text')->willReturn(true);
        $this->typeInstance->method('getFlags')->willReturn([]);
        $this->assertTrue(in_array('is_full_text', $this->getInstance(['full_text' => true])->getFlags()));
        $this->assertFalse(in_array('is_full_text', $this->getInstance(['full_text' => false])->getFlags()));
    }

    /**
     * @covers \App\Umc\MagentoBundle\Model\Attribute::getOptionType
     * @covers \App\Umc\MagentoBundle\Model\Attribute::__construct
     */
    public function testGetOptionType()
    {
        $option1 = $this->createMock(Option::class);
        $option1->expects($this->once())->method('getValue')->willReturn(10);
        $option2 = $this->createMock(Option::class);
        $option2->expects($this->once())->method('getValue')->willReturn('value');
        $this->optionFactory->expects($this->exactly(2))->method('create')
            ->willReturnOnConsecutiveCalls($option1, $option2);
        $this->typeInstance->method('hasFlag')->with('manual_options')->willReturn(true);
        $instance = $this->getInstance(['_option' => [[], []]]);
        $this->assertEquals('string', $instance->getOptionType());
    }

    /**
     * @covers \App\Umc\MagentoBundle\Model\Attribute::getOptionType
     * @covers \App\Umc\MagentoBundle\Model\Attribute::__construct
     */
    public function testGetOptionTypeNoOptions()
    {
        $this->typeInstance->method('hasFlag')->with('manual_options')->willReturn(true);
        $this->assertEquals('number', $this->getInstance([])->getOptionType());
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
