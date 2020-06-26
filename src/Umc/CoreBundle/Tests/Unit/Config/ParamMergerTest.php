<?php

/**
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

namespace App\Umc\CoreBundle\Tests\Unit\Config;

use App\Umc\CoreBundle\Config\ParamMerger;
use App\Umc\CoreBundle\Util\Sorter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ParamMergerTest extends TestCase
{
    /**
     * @var Sorter | MockObject
     */
    private $sorter;
    /**
     * @var ParameterBagInterface | MockObject
     */
    private $parameterBag;
    /**
     * @var ParamMerger
     */
    private $merger;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->sorter = $this->createMock(Sorter::class);
        $this->parameterBag = $this->createMock(ParameterBagInterface::class);
        $this->merger = new ParamMerger($this->sorter);
    }

    /**
     * @covers \App\Umc\CoreBundle\Config\ParamMerger::mergeParams
     * @covers \App\Umc\CoreBundle\Config\ParamMerger::__construct
     */
    public function testMergeParams()
    {
        $this->parameterBag->method('get')->willReturn([
            'key1' => ['val' => 'value1'],
            'key2' => ['val' => 'value2']
        ]);
        $param = [
            'param2' => [
                'flags' => [
                    'extends' => 'param1',
                    'sort' => 'sort_order',
                    'filter' => 'enabled'
                ],
                'value' => [
                    'key1' => ['val' => 'value11'],
                    'key3' => ['val' => 'value3']
                ]
            ],
            'param3' => [
                'flags' => [
                    'extends' => 'param2',
                    'sort' => 'sort_order',
                    'filter' => '!enabled'
                ],
                'value' => [
                    'key1' => [
                        'val' => 'value111',
                        'enabled' => true
                    ],
                    'key3' => [
                        'val' => 'value111',
                    ]
                ]
            ]
        ];
        $this->sorter->method('sort')->willReturnArgument(0);
        $expected = [
            'param2' => [
                'key1' => ['val' => 'value11'],
                'key2' => ['val' => 'value2'],
                'key3' => ['val' => 'value3']
            ],
            'param3' => [
                'key1' => ['val' => 'value111', 'enabled' => true],
            ]
        ];
        $result = $this->merger->mergeParams($this->parameterBag, $param);
        $this->assertEquals($expected, $result);
    }

    /**
     * @covers \App\Umc\CoreBundle\Config\ParamMerger::mergeParams
     * @covers \App\Umc\CoreBundle\Config\ParamMerger::__construct
     */
    public function testMergeParamsWithMissingParam()
    {
        $this->parameterBag->method('get')->willThrowException($this->createMock(ParameterNotFoundException::class));
        $param = [
            'param2' => [
                'flags' => [
                    'extends' => 'param1',
                ],
                'value' => [
                    'key1' => ['val' => 'value11'],
                    'key3' => ['val' => 'value3']
                ]
            ],
        ];
        $expected = [
            'param2' => [
                'key1' => ['val' => 'value11'],
                'key3' => ['val' => 'value3']
            ]
        ];
        $this->assertEquals($expected, $this->merger->mergeParams($this->parameterBag, $param));
    }
}
