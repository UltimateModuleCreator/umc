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

namespace App\Test\Model\Attribute\Type;

use App\Model\Attribute;
use App\Model\Attribute\Type\BaseType;
use App\Model\Attribute\Type\Factory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

class FactoryTest extends TestCase
{
    /**
     * @var Attribute | MockObject
     */
    private $attribute;
    /**
     * @var Environment | MockObject
     */
    private $twig;
    /**
     * @var Factory
     */
    private $factory;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->twig = $this->createMock(Environment::class);
        $this->attribute = $this->createMock(Attribute::class);
        $this->factory = new Factory($this->twig, ['type' => ['data']]);
    }

    /**
     * @covers \App\Model\Attribute\Type\Factory::create
     * @covers \App\Model\Attribute\Type\Factory::__construct
     */
    public function testCreate()
    {
        $this->attribute->method('getType')->willReturn('type');
        $this->assertInstanceOf(BaseType::class, $this->factory->create($this->attribute));
    }

    /**
     * @covers \App\Model\Attribute\Type\Factory::create
     * @covers \App\Model\Attribute\Type\Factory::__construct
     */
    public function testCreateMissingType()
    {
        $this->attribute->method('getType')->willReturn('dummy');
        $this->expectException(\InvalidArgumentException::class);
        $this->factory->create($this->attribute);
    }
}
