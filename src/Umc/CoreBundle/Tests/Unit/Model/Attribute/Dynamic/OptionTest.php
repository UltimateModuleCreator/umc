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

namespace App\Umc\CoreBundle\Tests\Unit\Model\Attribute\Dynamic;

use App\Umc\CoreBundle\Model\Attribute\Dynamic;
use App\Umc\CoreBundle\Model\Attribute\Dynamic\Option;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class OptionTest extends TestCase
{
    /**
     * @var Dynamic | MockObject
     */
    private $dynamic;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->dynamic = $this->createMock(Dynamic::class);
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic\Option::getValue
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic\Option::__construct
     */
    public function testGetValue()
    {
        $this->assertEquals('', $this->getInstance([])->getValue());
        $this->assertEquals('option', $this->getInstance(['value' => 'option'])->getValue());
        $this->assertEquals('1', $this->getInstance(['value' => true])->getValue());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic\Option::getLabel
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic\Option::__construct
     */
    public function testGetLabel()
    {
        $this->assertEquals('', $this->getInstance([])->getLabel());
        $this->assertEquals('label', $this->getInstance(['label' => 'label'])->getLabel());
        $this->assertEquals('1', $this->getInstance(['label' => true])->getLabel());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic\Option::isDefaultRadio
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic\Option::__construct
     */
    public function testIsDefaultRadio()
    {
        $this->assertFalse($this->getInstance([])->isDefaultRadio());
        $this->assertTrue($this->getInstance(['default_radio' => true])->isDefaultRadio());
        $this->assertTrue($this->getInstance(['default_radio' => 1])->isDefaultRadio());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic\Option::isDefaultCheckbox
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic\Option::__construct
     */
    public function testIsDefaultCheckbox()
    {
        $this->assertFalse($this->getInstance([])->isDefaultCheckbox());
        $this->assertTrue($this->getInstance(['default_checkbox' => true])->isDefaultCheckbox());
        $this->assertTrue($this->getInstance(['default_checkbox' => 1])->isDefaultCheckbox());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic\Option::getField
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic\Option::__construct
     */
    public function testGetField()
    {
        $this->assertEquals($this->dynamic, $this->getInstance([])->getField());
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic\Option::toArray
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic\Option::__construct
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
        return new Option($this->dynamic, $data);
    }
}
