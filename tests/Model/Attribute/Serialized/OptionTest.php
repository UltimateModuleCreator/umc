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

namespace App\Test\Model\Attribute\Serialized;

use App\Model\Attribute\Serialized;
use App\Model\Attribute\Serialized\Option;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class OptionTest extends TestCase
{
    /**
     * @var Serialized | MockObject
     */
    private $serialized;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->serialized = $this->createMock(Serialized::class);
    }

    /**
     * @covers \App\Model\Attribute\Serialized\Option::getValue
     * @covers \App\Model\Attribute\Serialized\Option::__construct
     */
    public function testGetValue()
    {
        $this->assertEquals('', $this->getInstance([])->getValue());
        $this->assertEquals('option', $this->getInstance(['value' => 'option'])->getValue());
        $this->assertEquals('1', $this->getInstance(['value' => true])->getValue());
    }

    /**
     * @covers \App\Model\Attribute\Serialized\Option::getLabel
     * @covers \App\Model\Attribute\Serialized\Option::__construct
     */
    public function testGetLabel()
    {
        $this->assertEquals('', $this->getInstance([])->getLabel());
        $this->assertEquals('label', $this->getInstance(['label' => 'label'])->getLabel());
        $this->assertEquals('1', $this->getInstance(['label' => true])->getLabel());
    }

    /**
     * @covers \App\Model\Attribute\Serialized\Option::isDefaultRadio
     * @covers \App\Model\Attribute\Serialized\Option::__construct
     */
    public function testIsDefaultRadio()
    {
        $this->assertFalse($this->getInstance([])->isDefaultRadio());
        $this->assertTrue($this->getInstance(['default_radio' => true])->isDefaultRadio());
        $this->assertTrue($this->getInstance(['default_radio' => 1])->isDefaultRadio());
    }

    /**
     * @covers \App\Model\Attribute\Serialized\Option::isDefaultCheckbox
     * @covers \App\Model\Attribute\Serialized\Option::__construct
     */
    public function testIsDefaultCheckbox()
    {
        $this->assertFalse($this->getInstance([])->isDefaultCheckbox());
        $this->assertTrue($this->getInstance(['default_checkbox' => true])->isDefaultCheckbox());
        $this->assertTrue($this->getInstance(['default_checkbox' => 1])->isDefaultCheckbox());
    }

    /**
     * @covers \App\Model\Attribute\Serialized\Option::getField
     * @covers \App\Model\Attribute\Serialized\Option::__construct
     */
    public function testGetField()
    {
        $this->assertEquals($this->serialized, $this->getInstance([])->getField());
    }

    /**
     * @covers \App\Model\Attribute\Serialized\Option::toArray
     * @covers \App\Model\Attribute\Serialized\Option::__construct
     */
    public function testToArray()
    {
        $result = $this->getInstance([])->toArray();
        $this->assertArrayHasKey('value', $result);
        $this->assertArrayHasKey('label', $result);
        $this->assertArrayHasKey('default_checkbox', $result);
        $this->assertArrayHasKey('default_radio', $result);
    }

    /**
     * @param array $data
     * @return Option
     */
    private function getInstance(array $data): Option
    {
        return new Option($this->serialized, $data);
    }
}
