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

namespace App\Tests\Model\Entity;

use App\Model\Attribute\Factory as AttributeFactory;
use App\Model\Entity;
use App\Model\Entity\Factory;
use App\Model\Module;
use App\Util\StringUtil;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    /**
     * @var AttributeFactory | MockObject
     */
    private $attributeFactory;
    /**
     * @var StringUtil | MockObject
     */
    private $stringUtil;
    /**
     * @var Factory
     */
    private $factory;
    /**
     * @var Module | MockObject
     */
    private $module;

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->attributeFactory = $this->createMock(AttributeFactory::class);
        $this->stringUtil = $this->createMock(StringUtil::class);
        $this->module = $this->createMock(Module::class);
        $this->factory = new Factory(
            $this->attributeFactory,
            $this->stringUtil
        );
    }

    /**
     * @covers \App\Model\Entity\Factory::create
     * @covers \App\Model\Entity\Factory::__construct
     */
    public function testCreate()
    {
        $this->assertInstanceOf(Entity::class, $this->factory->create($this->module));
        $this->assertInstanceOf(Entity::class, $this->factory->create($this->module, ['data']));
    }
}
