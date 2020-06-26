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

use App\Umc\CoreBundle\Config\FileLoader;
use App\Umc\CoreBundle\Config\Provider;
use PHPUnit\Framework\TestCase;

class ProviderTest extends TestCase
{
    /**
     * @covers \App\Umc\CoreBundle\Config\Provider::getConfig
     * @covers \App\Umc\CoreBundle\Config\Provider::__construct
     */
    public function testGetConfig()
    {
        $loader = $this->createMock(FileLoader::class);
        $provider = new Provider($loader, 'file');
        $loader->method('load')->with('file')->willReturn(['content']);
        $this->assertEquals(['content'], $provider->getConfig());
    }
}
