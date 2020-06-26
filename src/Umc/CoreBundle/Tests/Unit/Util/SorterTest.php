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

namespace App\Umc\CoreBundle\Tests\Unit\Util;

use App\Umc\CoreBundle\Util\Sorter;
use PHPUnit\Framework\TestCase;

class SorterTest extends TestCase
{
    /**
     * @covers \App\Umc\CoreBundle\Util\Sorter::sort
     */
    public function testSortAscending()
    {
        $sorter = new Sorter();
        $data = [
            1 => ['sort_order' => 1],
            2 => ['sort_order' => 2],
            3 => [],
            4 => ['sort_order' => 0],
        ];
        $expected = [
            3 => [],
            4 => ['sort_order' => 0],
            1 => ['sort_order' => 1],
            2 => ['sort_order' => 2],
        ];
        $this->assertEquals($expected, $sorter->sort($data));
    }

    /**
     * @covers \App\Umc\CoreBundle\Util\Sorter::sort
     */
    public function testSortDescending()
    {
        $sorter = new Sorter();
        $data = [
            1 => ['sort_order' => 1],
            2 => ['sort_order' => 2],
            3 => [],
            4 => ['sort_order' => 0],
        ];
        $expected = [
            2 => ['sort_order' => 2],
            1 => ['sort_order' => 1],
            4 => ['sort_order' => 0],
            3 => [],
        ];
        $this->assertEquals($expected, $sorter->sort($data, 'sort_order', 0, true));
    }
}
