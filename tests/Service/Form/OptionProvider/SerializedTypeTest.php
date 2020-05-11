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

namespace App\Tests\Service\Form\OptionProvider;

use App\Service\Form\OptionProvider\AttributeType;
use App\Service\Form\OptionProvider\SerializedType;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SerializedTypeTest extends TestCase
{
    /**
     * @var AttributeType | MockObject
     */
    private $attributeType;
    /**
     * @var SerializedType
     */
    private $serializedType;

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->attributeType = $this->createMock(AttributeType::class);
        $this->serializedType = new SerializedType(
            $this->attributeType
        );
    }

    /**
     * @covers \App\Service\Form\OptionProvider\SerializedType::getOptions
     * @covers \App\Service\Form\OptionProvider\SerializedType::__construct
     */
    public function testGetOptions()
    {
        $this->attributeType->expects($this->once())->method('getOptions')->willReturn([
            'group1' => [
                [
                    'label' => 'label1',
                    'can_be_serialized' => true
                ],
                [
                    'label' => 'label2',
                    'can_be_serialized' => false
                ],
            ],
            'group2' => [
                [
                    'label' => 'label1',
                    'can_be_serialized' => true
                ],
                [
                    'label' => 'label2',
                ],
            ]
        ]);
        $expected = [
            'group1' => [
                [
                    'label' => 'label1',
                    'can_be_serialized' => true
                ],
            ],
            'group2' => [
                [
                    'label' => 'label1',
                    'can_be_serialized' => true
                ],
            ]
        ];
        $this->assertEquals($expected, $this->serializedType->getOptions());
        //call twice to test memoizing
        $this->assertEquals($expected, $this->serializedType->getOptions());
    }
}
