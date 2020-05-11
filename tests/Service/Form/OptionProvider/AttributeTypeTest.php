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
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class AttributeTypeTest extends TestCase
{
    /**
     * @var ContainerBagInterface | MockObject
     */
    private $containerBag;
    /**
     * @var AttributeType
     */
    private $attributeType;

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->containerBag = $this->createMock(ContainerBagInterface::class);
        $this->attributeType = new AttributeType(
            $this->containerBag
        );
    }

    /**
     * @covers \App\Service\Form\OptionProvider\AttributeType::getOptions
     * @covers \App\Service\Form\OptionProvider\AttributeType::__construct
     */
    public function testGetOptions()
    {
        $this->containerBag->expects($this->once())->method('get')->willReturn(
            [
                'type1' => [
                    'label' => 'label1',
                    'input_type' => 'group1'
                ],
                'type2' => [
                    'input_type' => 'group1'
                ],
                'type3' => [
                    'input_type' => 'group2'
                ],
                'type4' => [
                    'dummy' => 'dummy'
                ],
            ]
        );
        $expected = [
            'group1' => [
                'type1' => [
                    'label' => 'label1',
                    'input_type' => 'group1',
                    'value' => 'type1'
                ],
                'type2' => [
                    'input_type' => 'group1',
                    'value' => 'type2'
                ],
            ],
            'group2' => [
                'type3' => [
                    'input_type' => 'group2',
                    'value' => 'type3'
                ],
            ],
            'Misc' => [
                'type4' => [
                    'dummy' => 'dummy',
                    'value' => 'type4'
                ],
            ]
        ];
        $this->assertEquals($expected, $this->attributeType->getOptions());
        //call twice to test memoizing
        $this->assertEquals($expected, $this->attributeType->getOptions());
    }

    /**
     * @covers \App\Service\Form\OptionProvider\AttributeType::getOptions
     * @covers \App\Service\Form\OptionProvider\AttributeType::__construct
     */
    public function testGetOptionsNotArray()
    {
        $this->containerBag->expects($this->once())->method('get')->willReturn('dummy');
        $this->assertEquals([], $this->attributeType->getOptions());
        //call twice to test memoizing
        $this->assertEquals([], $this->attributeType->getOptions());
    }
}
