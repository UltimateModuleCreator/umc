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
use App\Model\Attribute\Type\Dropdown;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

class DropdownTest extends TestCase
{
    /**
     * @var Environment | MockObject
     */
    private $twig;
    /**
     * @var Attribute | MockObject
     */
    private $attribute;
    /**
     * @var Dropdown
     */
    private $dropdown;

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->twig = $this->createMock(Environment::class);
        $this->attribute = $this->createMock(Attribute::class);
        $this->dropdown = new Dropdown(
            $this->twig,
            $this->attribute,
            [
                'schema_type' => 'default_schema',
                'schema_attributes' => 'default_schema_attributes'
            ]
        );
    }

    /**
     * @covers \App\Model\Attribute\Type\Dropdown::getSchemaType
     * @covers \App\Model\Attribute\Type\Dropdown::isTextAttribute
     * @covers \App\Model\Attribute\Type\Dropdown::__construct
     */
    public function testGetSchemaType()
    {
        $this->attribute->expects($this->once())->method('getOptions')->willReturn([
            $this->getOptionMock(1),
            $this->getOptionMock('text')
        ]);
        $this->assertEquals('varchar', $this->dropdown->getSchemaType());
    }

    /**
     * @covers \App\Model\Attribute\Type\Dropdown::getSchemaType
     * @covers \App\Model\Attribute\Type\Dropdown::isTextAttribute
     * @covers \App\Model\Attribute\Type\Dropdown::__construct
     */
    public function testGetSchemaTypeNumeric()
    {
        $this->attribute->expects($this->once())->method('getOptions')->willReturn([
            $this->getOptionMock(1),
            $this->getOptionMock(2)
        ]);
        $this->assertEquals('default_schema', $this->dropdown->getSchemaType());
    }

    /**
     * @covers \App\Model\Attribute\Type\Dropdown::getSchemaAttributes
     * @covers \App\Model\Attribute\Type\Dropdown::isTextAttribute
     * @covers \App\Model\Attribute\Type\Dropdown::__construct
     */
    public function testGetSchemaAttributes()
    {
        $this->attribute->expects($this->once())->method('getOptions')->willReturn([
            $this->getOptionMock(1),
            $this->getOptionMock('text')
        ]);
        $this->assertEquals(' length="255"', $this->dropdown->getSchemaAttributes());
    }

    /**
     * @covers \App\Model\Attribute\Type\Dropdown::getSchemaAttributes
     * @covers \App\Model\Attribute\Type\Dropdown::isTextAttribute
     * @covers \App\Model\Attribute\Type\Dropdown::__construct
     */
    public function testGetSchemaAttributesNumeric()
    {
        $this->attribute->expects($this->once())->method('getOptions')->willReturn([
            $this->getOptionMock(1),
            $this->getOptionMock(2)
        ]);
        $this->assertEquals('default_schema_attributes', $this->dropdown->getSchemaAttributes());
    }

    /**
     * @param $value
     * @return MockObject
     */
    private function getOptionMock($value)
    {
        $mock = $this->createMock(Attribute\Option::class);
        $mock->method('getValue')->willReturn($value);
        return $mock;
    }
}
