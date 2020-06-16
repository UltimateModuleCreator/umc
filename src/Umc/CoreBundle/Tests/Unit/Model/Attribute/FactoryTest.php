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

namespace App\Umc\CoreBundle\Tests\Unit\Model\Attribute;

use App\Umc\CoreBundle\Model\Attribute;
use App\Umc\CoreBundle\Model\Attribute\Factory;
use App\Umc\CoreBundle\Model\Attribute\Type\Factory as TypeFactory;
use App\Umc\CoreBundle\Model\Attribute\Option\Factory as OptionFactory;
use App\Umc\CoreBundle\Model\Attribute\Dynamic\Factory as DynamicFactory;
use App\Umc\CoreBundle\Model\Entity;
use App\Umc\CoreBundle\Util\StringUtil;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    /**
     * @var Factory
     */
    private $factory;
    /**
     * @var Entity | MockObject
     */
    private $entity;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $typeFactory = $this->createMock(TypeFactory::class);
        $optionFactory = $this->createMock(OptionFactory::class);
        $dynamicFactory = $this->createMock(DynamicFactory::class);
        $stringUtil = $this->createMock(StringUtil::class);
        $this->entity = $this->createMock(Entity::class);
        $this->factory = new Factory(
            $typeFactory,
            $optionFactory,
            $dynamicFactory,
            $stringUtil
        );
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Factory::create
     * @covers \App\Umc\CoreBundle\Model\Attribute\Factory::__construct
     */
    public function testCreate()
    {
        $this->assertInstanceOf(Attribute::class, $this->factory->create($this->entity));
        $this->assertInstanceOf(Attribute::class, $this->factory->create($this->entity), ['data']);
    }
}
