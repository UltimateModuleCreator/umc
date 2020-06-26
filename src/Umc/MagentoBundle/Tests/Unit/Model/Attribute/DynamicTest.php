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

namespace App\Umc\MagentoBundle\Tests\Unit\Model\Attribute;

use App\Umc\CoreBundle\Model\Attribute;
use App\Umc\MagentoBundle\Model\Attribute\Dynamic;
use App\Umc\CoreBundle\Model\Attribute\Dynamic\Option\Factory as OptionFactory;
use App\Umc\CoreBundle\Model\Attribute\Dynamic\Type\Factory as TypeFactory;
use App\Umc\CoreBundle\Util\StringUtil;
use App\Umc\MagentoBundle\Model\Entity;
use App\Umc\MagentoBundle\Model\Module;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DynamicTest extends TestCase
{
    /**
     * @var OptionFactory | MockObject
     */
    private $optionFactory;
    /**
     * @var TypeFactory | MockObject
     */
    private $typeFactory;
    /**
     * @var StringUtil | MockObject
     */
    private $stringUtil;
    /**
     * @var Attribute | MockObject
     */
    private $attribute;
    /**
     * @var Dynamic
     */
    private $dynamic;
    /**
     * @var Dynamic\Type\BaseType | MockObject
     */
    private $type;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->optionFactory = $this->createMock(OptionFactory::class);
        $this->typeFactory = $this->createMock(TypeFactory::class);
        $this->stringUtil = $this->createMock(StringUtil::class);
        $this->attribute = $this->createMock(Attribute::class);
        $this->type = $this->createMock(Dynamic\Type\BaseType::class);
        $this->typeFactory->method('create')->willReturn($this->type);
        $this->dynamic = new Dynamic(
            $this->optionFactory,
            $this->typeFactory,
            $this->stringUtil,
            $this->attribute,
            []
        );
    }

    /**
     * @covers \App\Umc\MagentoBundle\Model\Attribute\Dynamic::getOptionSourceVirtualType
     */
    public function testGetOptionSourceVirtualType()
    {
        $this->attribute->method('getCode')->willReturn('attribute_code');
        $entity = $this->createMock(Entity::class);
        $module = $this->createMock(Module::class);
        $this->attribute->method('getEntity')->willReturn($entity);
        $entity->method('getModule')->willReturn($module);
        $entity->method('getNameSingular')->willReturn('entity_name');
        $module->method('getModuleName')->willReturn('ModuleName');
        $this->stringUtil->expects($this->once())->method('glueClassParts')
            ->with([
                'ModuleName',
                'entity_name',
                'Source',
                'attribute_code',
                'dynamic_code'
            ])
            ->willReturn('source_model');
        $instance = new Dynamic(
            $this->optionFactory,
            $this->typeFactory,
            $this->stringUtil,
            $this->attribute,
            ['code' => 'dynamic_code']
        );
        $this->assertEquals('source_model', $instance->getOptionSourceVirtualType());
    }
}
