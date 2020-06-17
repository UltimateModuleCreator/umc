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

namespace App\Umc\CoreBundle\Tests\Unit\Config\Modifier;

use App\Umc\CoreBundle\Config\Modifier\Sort;
use App\Umc\CoreBundle\Util\Sorter;
use PHPUnit\Framework\TestCase;

class SortTest extends TestCase
{
    /**
     * @covers \App\Umc\CoreBundle\Config\Modifier\Sort::modify
     * @covers \App\Umc\CoreBundle\Config\Modifier\Sort::__construct
     */
    public function testModify()
    {
        $sorter = $this->createMock(Sorter::class);
        $sorter->expects($this->exactly(2))->method('sort')->willReturnArgument(0);
        $config = [
            'item1' => [
                'item11' => [
                    'item111' => [],
                    'item112' => []
                ]
            ],
            'item2' => [
                'item21' => [
                    'item111' => [],
                    'item112' => []
                ]
            ]
        ];
        $sort = new Sort($sorter, ['/' => ['key' => 'sort_order'], 'item11' => ['key' => 'sort_order']]);
        $this->assertEquals($config, $sort->modify($config));
    }
}
