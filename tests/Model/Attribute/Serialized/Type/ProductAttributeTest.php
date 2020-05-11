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

namespace App\Test\Model\Attribute\Serialized\Type;

use App\Model\Attribute;
use App\Model\Attribute\Serialized;
use App\Model\Attribute\Serialized\Type\ProductAttribute;
use App\Model\Entity;
use App\Model\Module;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

class ProductAttributeTest extends TestCase
{
    /**
     * @var Environment | MockObject
     */
    private $twig;
    /**
     * @var Serialized | MockObject
     */
    private $serialized;
    /**
     * @var ProductAttribute
     */
    private $productAttribute;

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->twig = $this->createMock(Environment::class);
        $this->serialized = $this->createMock(Serialized::class);
        $this->productAttribute = new ProductAttribute(
            $this->twig,
            $this->serialized,
            []
        );
    }

    /**
     * @covers \App\Model\Attribute\Serialized\Type\ProductAttribute::getSourceModel
     */
    public function testGetSourceModel()
    {
        $attribute = $this->createMock(Attribute::class);
        $entity = $this->createMock(Entity::class);
        $module = $this->createMock(Module::class);
        $this->serialized->method('getAttribute')->willReturn($attribute);
        $attribute->method('getEntity')->willReturn($entity);
        $entity->method('getModule')->willReturn($module);
        $module->method('getUmcCrudNamespace')->willReturn('Namespace');
        $module->method('getUmcModuleName')->willReturn('Module');
        $expected = 'Namespace\Module\Source\Catalog\ProductAttribute';
        $this->assertEquals($expected, $this->productAttribute->getSourceModel());
    }

    /**
     * @covers \App\Model\Attribute\Serialized\Type\ProductAttribute::isProductAttribute
     */
    public function testIsProductAttribute()
    {
        $this->assertTrue($this->productAttribute->isProductAttribute());
    }
}
