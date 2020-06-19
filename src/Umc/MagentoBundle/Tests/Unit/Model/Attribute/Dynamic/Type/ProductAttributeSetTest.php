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

namespace App\Umc\MagentoBundle\Tests\Unit\Model\Attribute\Dynamic\Type;

use App\Umc\MagentoBundle\Model\Attribute;
use App\Umc\MagentoBundle\Model\Entity;
use App\Umc\MagentoBundle\Model\Module;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

class ProductAttributeSetTest extends TestCase
{
    /**
     * @covers \App\Umc\MagentoBundle\Model\Attribute\Dynamic\Type\ProductAttributeSet::getSourceModel
     */
    public function testGetSourceModel()
    {
        $twig = $this->createMock(Environment::class);
        $dynamic = $this->createMock(Attribute\Dynamic::class);
        $productAttribute = new Attribute\Dynamic\Type\ProductAttributeSet($twig, $dynamic, []);
        $attribute = $this->createMock(Attribute::class);
        $entity = $this->createMock(Entity::class);
        $module = $this->createMock(Module::class);
        $dynamic->method('getAttribute')->willReturn($attribute);
        $attribute->method('getEntity')->willReturn($entity);
        $entity->method('getModule')->willReturn($module);
        $module->method('getUmcCrudNamespace')->willReturn('Namespace');
        $module->method('getUmcModuleName')->willReturn('Module');
        $expected = 'Namespace\Module\Source\Catalog\ProductAttributeSet';
        $this->assertEquals($expected, $productAttribute->getSourceModel());
    }
}
