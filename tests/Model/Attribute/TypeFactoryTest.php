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
namespace App\Tests\Model\Attribute;

use App\Model\Attribute;
use App\Model\Attribute\Country;
use App\Model\Attribute\TypeFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TypeFactoryTest extends TestCase
{
    /**
     * @var \Twig_Environment | MockObject
     */
    private $twig;
    /**
     * @var array
     */
    private $map;
    /**
     * @var TypeFactory
     */
    private $factory;

    /**
     * setup tests;
     */
    protected function setUp()
    {
        $this->twig = $this->createMock(\Twig_Environment::class);
        $this->map = [
            'country' => [
                'class' => Country::class,
            ],
            'dummy-no-class' => [],
            'dummy-wrong-class' => [
                'class' => \stdClass::class
            ]
        ];
        $this->factory = new TypeFactory($this->map, $this->twig);
    }

    /**
     * @covers \App\Model\Attribute\TypeFactory::create()
     * @covers \App\Model\Attribute\TypeFactory::__construct()
     */
    public function testCreate()
    {
        $this->assertInstanceOf(Country::class, $this->factory->create($this->getAttributeMock('country')));
    }

    /**
     * @covers \App\Model\Attribute\TypeFactory::create()
     * @covers \App\Model\Attribute\TypeFactory::__construct()
     */
    public function testCreateWithNoType()
    {
        $this->expectException(\Exception::class);
        $this->factory->create($this->getAttributeMock('dummy-no-class'));
    }

    /**
     * @covers \App\Model\Attribute\TypeFactory::create()
     * @covers \App\Model\Attribute\TypeFactory::__construct()
     */
    public function testCreateWithWrongType()
    {
        $this->expectException(\Exception::class);
        $this->factory->create($this->getAttributeMock('dummy-wrong-class'));
    }

    /**
     * @covers \App\Model\Attribute\TypeFactory::create()
     * @covers \App\Model\Attribute\TypeFactory::__construct()
     */
    public function testCreateWithWrongTypeMap()
    {
        $this->expectException(\Exception::class);
        $this->factory->create($this->getAttributeMock('missing'));
    }

    /**
     * @param $type
     * @return MockObject | Attribute
     */
    private function getAttributeMock($type)
    {
        $attribute = $this->createMock(Attribute::class);
        $attribute->method('getType')->willReturn($type);
        return $attribute;
    }
}
