<?php
declare(strict_types=1);

namespace App\Test\Model\Attribute\Serialized\Type;

use App\Model\Attribute;
use App\Model\Attribute\Serialized;
use App\Model\Attribute\Serialized\Type\ProductAttributeSet;
use App\Model\Entity;
use App\Model\Module;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

class ProductAttributeSetTest extends TestCase
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
     * @var ProductAttributeSet
     */
    private $productAttributeSet;

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->twig = $this->createMock(Environment::class);
        $this->serialized = $this->createMock(Serialized::class);
        $this->productAttributeSet = new ProductAttributeSet(
            $this->twig,
            $this->serialized,
            []
        );
    }

    /**
     * @covers \App\Model\Attribute\Serialized\Type\ProductAttributeSet::getSourceModel
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
        $expected = 'Namespace\Module\Source\Catalog\ProductAttributeSet';
        $this->assertEquals($expected, $this->productAttributeSet->getSourceModel());
    }

    /**
     * @covers \App\Model\Attribute\Serialized\Type\ProductAttributeSet::isProductAttributeSet
     */
    public function testIsProductAttribute()
    {
        $this->assertTrue($this->productAttributeSet->isProductAttributeSet());
    }
}
