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

namespace App\Umc\MagentoBundle\Tests\Unit\Model\Attribute\Type;

use App\Umc\MagentoBundle\Model\Attribute;
use App\Umc\MagentoBundle\Model\Entity;
use App\Umc\MagentoBundle\Model\Module;
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
     * @var Attribute | MockObject
     */
    private $attribute;
    /**
     * @var Attribute\Type\ProductAttributeSet
     */
    private $productAttributeSet;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->twig = $this->createMock(Environment::class);
        $this->attribute = $this->createMock(Attribute::class);
        $this->productAttributeSet = new Attribute\Type\ProductAttributeSet(
            $this->twig,
            $this->attribute,
            []
        );
    }

    /**
     * @covers \App\Umc\MagentoBundle\Model\Attribute\Type\ProductAttributeSet::getSourceModel
     */
    public function testGetSourceModel()
    {
        $entity = $this->createMock(Entity::class);
        $module = $this->createMock(Module::class);
        $this->attribute->method('getEntity')->willReturn($entity);
        $entity->method('getModule')->willReturn($module);
        $module->method('getUmcCrudNamespace')->willReturn('Namespace');
        $module->method('getUmcModuleName')->willReturn('Module');
        $expected = 'Namespace\Module\Source\Catalog\ProductAttributeSet';
        $this->assertEquals($expected, $this->productAttributeSet->getSourceModel());
    }
}
