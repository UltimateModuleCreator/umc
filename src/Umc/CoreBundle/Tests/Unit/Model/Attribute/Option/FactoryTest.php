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

namespace App\Umc\CoreBundle\Tests\Unit\Model\Attribute\Option;

use App\Umc\CoreBundle\Model\Attribute;
use App\Umc\CoreBundle\Model\Attribute\Option;
use App\Umc\CoreBundle\Model\Attribute\Option\Factory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    /**
     * @var Attribute | MockObject
     */
    private $attribute;
    /**
     * @var Factory
     */
    private $factory;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->attribute = $this->createMock(Attribute::class);
        $this->factory = new Factory();
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Option\Factory::create
     */
    public function testCreate()
    {
        $this->assertInstanceOf(Option::class, $this->factory->create($this->attribute));
        $this->assertInstanceOf(Option::class, $this->factory->create($this->attribute, ['data']));
    }
}
