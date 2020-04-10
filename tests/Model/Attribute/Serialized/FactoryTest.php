<?php
declare(strict_types=1);

namespace App\Test\Model\Attribute\Serialized;

use App\Model\Attribute;
use App\Model\Attribute\Serialized\Factory;
use App\Model\Attribute\Serialized\Option\Factory as OptionFactory;
use App\Model\Attribute\Serialized\Type\Factory as TypeFactory;
use App\Util\StringUtil;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    /**
     * @var OptionFactory | MockObject
     */
    private $optionFactory;
    /**
     * @var StringUtil | MockObject
     */
    private $stringUtil;
    /**
     * @var TypeFactory | MockObject
     */
    private $typeFactory;
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
        $this->optionFactory = $this->createMock(OptionFactory::class);
        $this->stringUtil = $this->createMock(StringUtil::class);
        $this->typeFactory = $this->createMock(TypeFactory::class);
        $this->attribute = $this->createMock(Attribute::class);
        $this->factory = new Factory(
            $this->optionFactory,
            $this->stringUtil,
            $this->typeFactory
        );
    }

    /**
     * @covers \App\Model\Attribute\Serialized\Factory::create
     * @covers \App\Model\Attribute\Serialized\Factory::__construct
     */
    public function testCreate()
    {
        $this->assertInstanceOf(Attribute\Serialized::class, $this->factory->create($this->attribute));
    }
}
