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

namespace App\Tests\Model\Attribute;

use App\Model\Attribute;
use App\Model\Attribute\Factory;
use App\Model\Attribute\Type\Factory as TypeFactory;
use App\Model\Attribute\Option\Factory as OptionFactory;
use App\Model\Attribute\Serialized\Factory as SerializedFactory;
use App\Model\Entity;
use App\Util\StringUtil;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class FactoryTest extends TestCase
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
     * @var SerializedFactory | MockObject
     */
    private $serializedFactory;
    /**
     * @var StringUtil | MockObject
     */
    private $stringUtil;
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
        $this->typeFactory = $this->createMock(TypeFactory::class);
        $this->optionFactory = $this->createMock(OptionFactory::class);
        $this->serializedFactory = $this->createMock(SerializedFactory::class);
        $this->stringUtil = $this->createMock(StringUtil::class);
        $this->entity = $this->createMock(Entity::class);
        $this->factory = new Factory(
            $this->typeFactory,
            $this->optionFactory,
            $this->serializedFactory,
            $this->stringUtil
        );
    }

    /**
     * @covers \App\Model\Attribute\Factory::create
     * @covers \App\Model\Attribute\Factory::__construct
     */
    public function testCreate()
    {
        $this->assertInstanceOf(Attribute::class, $this->factory->create($this->entity));
        $this->assertInstanceOf(Attribute::class, $this->factory->create($this->entity), ['data']);
    }
}
