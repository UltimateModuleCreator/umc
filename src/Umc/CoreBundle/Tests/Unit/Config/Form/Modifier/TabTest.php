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

namespace App\Umc\CoreBundle\Tests\Unit\Config\Form\Modifier;

use App\Umc\CoreBundle\Config\Form\Modifier\Tab;
use PHPUnit\Framework\TestCase;

class TabTest extends TestCase
{
    public function testModify()
    {
        $config = [
            'form1' => [
                'fields' => [
                    'item1' => ['code' => 'item1', 'tab' => 'tab1'],
                    'item2' => ['code' => 'item2', 'tab' => 'tab2'],
                ],
                'tabs' => [
                    'tab1' => [],
                    'tab3' => []
                ]
            ]
        ];
        $expected = [
            'form1' => [
                'tabs' => [
                    'tab1' => [
                        'fields' => [
                            'item1' => [
                                'code' => 'item1',
                                'tab' => 'tab1'
                            ]
                        ]
                    ],
                    'tab3' => []
                ]
            ]
        ];
        $tab = new Tab();
        $this->assertEquals($expected, $tab->modify($config));
    }
}
