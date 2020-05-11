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

namespace App\Test\Model\Attribute\Serialized\Option;

use App\Model\Attribute\Serialized;
use App\Model\Attribute\Serialized\Option;
use App\Model\Attribute\Serialized\Option\Factory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    /**
     * @var Serialized | MockObject
     */
    private $serialized;
    /**
     * @var Factory
     */
    private $factory;

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->serialized = $this->createMock(Serialized::class);
        $this->factory = new Factory();
    }

    /**
     * @covers \App\Model\Attribute\Serialized\Option\Factory::create
     */
    public function testCreate()
    {
        $this->assertInstanceOf(Option::class, $this->factory->create($this->serialized));
        $this->assertInstanceOf(Option::class, $this->factory->create($this->serialized, ['data']));
    }
}
