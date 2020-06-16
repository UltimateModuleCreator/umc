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

namespace App\Umc\CoreBundle\Tests\Unit\Model\Attribute\Dynamic\Option;

use App\Umc\CoreBundle\Model\Attribute\Dynamic;
use App\Umc\CoreBundle\Model\Attribute\Dynamic\Option;
use App\Umc\CoreBundle\Model\Attribute\Dynamic\Option\Factory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    /**
     * @var Dynamic | MockObject
     */
    private $dynamic;
    /**
     * @var Factory
     */
    private $factory;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->dynamic = $this->createMock(Dynamic::class);
        $this->factory = new Factory();
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic\Option\Factory::create
     */
    public function testCreate()
    {
        $this->assertInstanceOf(Option::class, $this->factory->create($this->dynamic));
        $this->assertInstanceOf(Option::class, $this->factory->create($this->dynamic, ['data']));
    }
}
