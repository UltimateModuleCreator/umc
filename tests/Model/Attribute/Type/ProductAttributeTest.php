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
use App\Model\Attribute\Type\ProductAttribute;
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
     * @var Attribute | MockObject
     */
    private $attribute;
    /**
     * @var ProductAttribute
     */
    private $productAttribute;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->twig = $this->createMock(Environment::class);
        $this->attribute = $this->createMock(Attribute::class);
        $this->productAttribute = new ProductAttribute(
            $this->twig,
            $this->attribute,
            []
        );
    }

    /**
     * @covers \App\Model\Attribute\Type\ProductAttribute::getSourceModel
     */
    public function testGetSourceModel()
    {
        $entity = $this->createMock(Entity::class);
        $module = $this->createMock(Module::class);
        $this->attribute->method('getEntity')->willReturn($entity);
        $entity->method('getModule')->willReturn($module);
        $module->method('getUmcCrudNamespace')->willReturn('Namespace');
        $module->method('getUmcModuleName')->willReturn('Module');
        $expected = 'Namespace\Module\Source\Catalog\ProductAttribute';
        $this->assertEquals($expected, $this->productAttribute->getSourceModel());
    }

    /**
     * @covers \App\Model\Attribute\Type\ProductAttribute::isProductAttribute
     */
    public function testIsProductAttribute()
    {
        $this->assertTrue($this->productAttribute->isProductAttribute());
    }
}
