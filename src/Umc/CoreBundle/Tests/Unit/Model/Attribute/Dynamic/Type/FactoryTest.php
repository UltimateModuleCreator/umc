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

namespace App\Umc\CoreBundle\Tests\Unit\Model\Attribute\Dynamic\Type;

use App\Umc\CoreBundle\Model\Attribute\Dynamic;
use App\Umc\CoreBundle\Model\Attribute\Dynamic\Type\Factory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

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
        $twig = $this->createMock(Environment::class);
        $this->dynamic = $this->createMock(Dynamic::class);
        $this->factory = new Factory(
            $twig,
            [
                'type' => [
                    'type' => 'type',
                    'can_be_dynamic' => true
                ],
                'invalid' => [
                    'type' => 'invalid',
                    'can_be_dynamic' => false
                ],
                'invalid2' => [
                    'type' => 'invalid2',
                ],
            ]
        );
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic\Type\Factory::create
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic\Type\Factory::__construct
     */
    public function testCreate()
    {
        $this->dynamic->method('getType')->willReturn('type');
        $this->assertInstanceOf(Dynamic\Type\BaseType::class, $this->factory->create($this->dynamic));
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic\Type\Factory::create
     */
    public function testCreateMissingType()
    {
        $this->dynamic->method('getType')->willReturn('dummy');
        $this->expectException(\InvalidArgumentException::class);
        $this->factory->create($this->dynamic);
    }
}
