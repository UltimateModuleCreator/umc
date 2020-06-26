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

use App\Umc\CoreBundle\Config\Modifier\Remove;
use PHPUnit\Framework\TestCase;

class RemoveTest extends TestCase
{
    /**
     * @covers \App\Umc\CoreBundle\Config\Modifier\Remove::modify
     * @covers \App\Umc\CoreBundle\Config\Modifier\Remove::__construct
     */
    public function testModify()
    {
        $config = [
            'level1' => [
                'level2' => [
                    'item1' => [
                        'data' => 'data'
                    ],
                    'item2' => [
                        'data' => 'data',
                        'enabled' => false
                    ],
                ],
            ],
            'level11' => [
                'data' => 'data',
                'enabled' => false
            ]
        ];
        $expected = [
            'level1' => [
                'level2' => [
                    'item1' => [
                        'data' => 'data'
                    ],
                ],
            ],
        ];
        $remove = new Remove('enabled');
        $this->assertEquals($expected, $remove->modify($config));
    }
}
