<?php
declare(strict_types=1);

namespace App\Test\Model\Attribute\Serialized\Type;

use App\Model\Attribute\Serialized;
use App\Model\Attribute\Serialized\Type\Factory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

class FactoryTest extends TestCase
{
    /**
     * @var Environment | MockObject
     */
    private $twig;
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
        $this->twig = $this->createMock(Environment::class);
        $this->serialized = $this->createMock(Serialized::class);
        $this->factory = new Factory(
            $this->twig,
            [
                'type' => [
                    'type' => 'type',
                    'can_be_serialized' => true
                ],
                'invalid' => [
                    'type' => 'invalid',
                    'can_be_serialized' => false
                ],
                'invalid2' => [
                    'type' => 'invalid2',
                ],
            ]
        );
    }

    /**
     * @covers \App\Model\Attribute\Serialized\Type\Factory::create
     * @covers \App\Model\Attribute\Serialized\Type\Factory::__construct
     */
    public function testCreate()
    {
        $this->serialized->method('getType')->willReturn('type');
        $this->assertInstanceOf(Serialized\Type\BaseType::class, $this->factory->create($this->serialized));
    }

    /**
     * @covers \App\Model\Attribute\Serialized\Type\Factory::create
     */
    public function testCreateMissingType()
    {
        $this->serialized->method('getType')->willReturn('dummy');
        $this->expectException(\InvalidArgumentException::class);
        $this->factory->create($this->serialized);
    }
}
