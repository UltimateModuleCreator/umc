<?php
declare(strict_types=1);

namespace App\Tests\Module;

use App\Model\Entity\Factory as EntityFactory;
use App\Model\Module;
use App\Model\Module\Factory;
use App\Util\StringUtil;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    /**
     * @var StringUtil | MockObject
     */
    private $stringUtil;
    /**
     * @var EntityFactory | MockObject
     */
    private $entityFactory;
    /**
     * @var Factory
     */
    private $factory;

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->stringUtil = $this->createMock(StringUtil::class);
        $this->entityFactory = $this->createMock(EntityFactory::class);
        $this->factory = new Factory(
            $this->stringUtil,
            $this->entityFactory,
            [],
            []
        );
    }

    /**
     * @covers \App\Model\Module\Factory::create
     * @covers \App\Model\Module\Factory::__construct
     */
    public function testCreate()
    {
        $this->assertInstanceOf(Module::class, $this->factory->create());
        $this->assertInstanceOf(Module::class, $this->factory->create(['data']));
    }
}
