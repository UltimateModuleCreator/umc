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

namespace App\Test\Model\Attribute\Type;

use App\Model\Attribute;
use App\Model\Attribute\Serialized as SerializedField;
use App\Model\Attribute\Type\Serialized;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

class SerializedTest extends TestCase
{
    /**
     * @var Environment | MockObject
     */
    private $twig;
    /**
     * @var Attribute | MockObject
     */
    private $attribute;
    /**
     * @var Serialized
     */
    private $serialized;

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->twig = $this->createMock(Environment::class);
        $this->attribute = $this->createMock(Attribute::class);
        $this->serialized = new Serialized(
            $this->twig,
            $this->attribute,
            []
        );
    }

    /**
     * @covers \App\Model\Attribute\Type\Serialized::isProductAttribute
     * @covers \App\Model\Attribute\Type\Serialized::__construct
     */
    public function testIsProductAttribute()
    {
        $instance1 = $this->createMock(SerializedField\Type\BaseType::class);
        $instance1->expects($this->once())->method('isProductAttribute')->willReturn(false);
        $serialized1 = $this->createMock(SerializedField::class);
        $serialized1->expects($this->once())->method('getTypeInstance')->willReturn($instance1);

        $instance2 = $this->createMock(SerializedField\Type\BaseType::class);
        $instance2->expects($this->once())->method('isProductAttribute')->willReturn(true);
        $serialized2 = $this->createMock(SerializedField::class);
        $serialized2->expects($this->once())->method('getTypeInstance')->willReturn($instance2);

        $serialized3 = $this->createMock(SerializedField::class);
        $serialized3->expects($this->never())->method('getTypeInstance');

        $this->attribute->expects($this->once())->method('getSerialized')
            ->willReturn([$serialized1, $serialized2, $serialized3]);

        $this->assertTrue($this->serialized->isProductAttribute());
        //call twice to test memoizing
        $this->assertTrue($this->serialized->isProductAttribute());
    }

    /**
     * @covers \App\Model\Attribute\Type\Serialized::isProductAttribute
     * @covers \App\Model\Attribute\Type\Serialized::__construct
     */
    public function testIsProductAttributeNoProductAttribute()
    {
        $instance1 = $this->createMock(SerializedField\Type\BaseType::class);
        $instance1->expects($this->once())->method('isProductAttribute')->willReturn(false);
        $serialized1 = $this->createMock(SerializedField::class);
        $serialized1->expects($this->once())->method('getTypeInstance')->willReturn($instance1);

        $instance2 = $this->createMock(SerializedField\Type\BaseType::class);
        $instance2->expects($this->once())->method('isProductAttribute')->willReturn(false);
        $serialized2 = $this->createMock(SerializedField::class);
        $serialized2->expects($this->once())->method('getTypeInstance')->willReturn($instance2);

        $this->attribute->expects($this->once())->method('getSerialized')
            ->willReturn([$serialized1, $serialized2]);

        $this->assertFalse($this->serialized->isProductAttribute());
        //call twice to test memoizing
        $this->assertFalse($this->serialized->isProductAttribute());
    }

    /**
     * @covers \App\Model\Attribute\Type\Serialized::isProductAttributeSet
     * @covers \App\Model\Attribute\Type\Serialized::__construct
     */
    public function testIsProductAttributeSet()
    {
        $instance1 = $this->createMock(SerializedField\Type\BaseType::class);
        $instance1->expects($this->once())->method('isProductAttributeSet')->willReturn(false);
        $serialized1 = $this->createMock(SerializedField::class);
        $serialized1->expects($this->once())->method('getTypeInstance')->willReturn($instance1);

        $instance2 = $this->createMock(SerializedField\Type\BaseType::class);
        $instance2->expects($this->once())->method('isProductAttributeSet')->willReturn(true);
        $serialized2 = $this->createMock(SerializedField::class);
        $serialized2->expects($this->once())->method('getTypeInstance')->willReturn($instance2);

        $serialized3 = $this->createMock(SerializedField::class);
        $serialized3->expects($this->never())->method('getTypeInstance');

        $this->attribute->expects($this->once())->method('getSerialized')
            ->willReturn([$serialized1, $serialized2, $serialized3]);

        $this->assertTrue($this->serialized->isProductAttributeSet());
        //call twice to test memoizing
        $this->assertTrue($this->serialized->isProductAttributeSet());
    }

    /**
     * @covers \App\Model\Attribute\Type\Serialized::isProductAttribute
     * @covers \App\Model\Attribute\Type\Serialized::__construct
     */
    public function testIsProductAttributeNoProductAttributeSet()
    {
        $instance1 = $this->createMock(SerializedField\Type\BaseType::class);
        $instance1->expects($this->once())->method('isProductAttributeSet')->willReturn(false);
        $serialized1 = $this->createMock(SerializedField::class);
        $serialized1->expects($this->once())->method('getTypeInstance')->willReturn($instance1);

        $instance2 = $this->createMock(SerializedField\Type\BaseType::class);
        $instance2->expects($this->once())->method('isProductAttributeSet')->willReturn(false);
        $serialized2 = $this->createMock(SerializedField::class);
        $serialized2->expects($this->once())->method('getTypeInstance')->willReturn($instance2);

        $this->attribute->expects($this->once())->method('getSerialized')
            ->willReturn([$serialized1, $serialized2]);

        $this->assertFalse($this->serialized->isProductAttributeSet());
        //call twice to test memoizing
        $this->assertFalse($this->serialized->isProductAttributeSet());
    }
}
