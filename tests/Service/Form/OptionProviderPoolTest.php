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

namespace App\Tests\Form;

use App\Service\Form\OptionProviderInterface;
use App\Service\Form\OptionProviderPool;
use PHPUnit\Framework\TestCase;

class OptionProviderPoolTest extends TestCase
{
    /**
     * @covers \App\Service\Form\OptionProviderPool::getProvider
     * @covers \App\Service\Form\OptionProviderPool::__construct
     */
    public function testGetProvider()
    {
        $provider1 = $this->createMock(OptionProviderInterface::class);
        $provider2 = $this->createMock(OptionProviderInterface::class);
        $pool = new OptionProviderPool([
            'one' => $provider1,
            'two' => $provider2
        ]);
        $this->assertEquals($provider1, $pool->getProvider('one'));
    }

    /**
     * @covers \App\Service\Form\OptionProviderPool::getProvider
     * @covers \App\Service\Form\OptionProviderPool::__construct
     */
    public function testGetProviderWithMissingProvider()
    {
        $provider1 = $this->createMock(OptionProviderInterface::class);
        $pool = new OptionProviderPool([
            'one' => $provider1,
        ]);
        $this->expectException(\InvalidArgumentException::class);
        $pool->getProvider('missing');
    }

    /**
     * @covers \App\Service\Form\OptionProviderPool::__construct
     */
    public function testGetProviderWithWrongConfiguration()
    {
        $provider1 = $this->createMock(OptionProviderInterface::class);
        $this->expectException(\InvalidArgumentException::class);
        new OptionProviderPool([
            'one' => $provider1,
            'two' => new \stdClass()
        ]);
    }
}
