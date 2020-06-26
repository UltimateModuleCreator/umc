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

use App\Umc\CoreBundle\Config\Modifier\Composite;
use App\Umc\CoreBundle\Config\Modifier\ModifierInterface;
use PHPUnit\Framework\TestCase;

class CompositeTest extends TestCase
{
    /**
     * @covers \App\Umc\CoreBundle\Config\Modifier\Composite::modify
     * @covers \App\Umc\CoreBundle\Config\Modifier\Composite::__construct
     */
    public function testModify()
    {
        $modifier1 = $this->createMock(ModifierInterface::class);
        $modifier1->method('modify')->willReturnCallback(
            function (array $config) {
                return array_map(
                    function ($item) {
                        return $item . '_modifier1';
                    },
                    $config
                );
            }
        );

        $modifier2 = $this->createMock(ModifierInterface::class);
        $modifier2->method('modify')->willReturnCallback(
            function (array $config) {
                return array_map(
                    function ($item) {
                        return $item . '_modifier2';
                    },
                    $config
                );
            }
        );
        $composite = new Composite([$modifier1, $modifier2]);
        $config = ['item1', 'item2'];
        $expected = ['item1_modifier1_modifier2', 'item2_modifier1_modifier2'];
        $this->assertEquals($expected, $composite->modify($config));
    }

    /**
     * @covers \App\Umc\CoreBundle\Config\Modifier\Composite::__construct
     */
    public function testConstructor()
    {
        $this->expectException(\InvalidArgumentException::class);
        new Composite(['dummy']);
    }
}
