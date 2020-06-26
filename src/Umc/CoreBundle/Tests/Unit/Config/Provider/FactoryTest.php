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

namespace App\Umc\CoreBundle\Tests\Unit\Config\Provider;

use App\Umc\CoreBundle\Config\FileLoader;
use App\Umc\CoreBundle\Config\Provider;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    /**
     * @covers \App\Umc\CoreBundle\Config\Provider\Factory::create
     * @covers \App\Umc\CoreBundle\Config\Provider\Factory::__construct
     */
    public function testCreate()
    {
        $loader = $this->createMock(FileLoader::class);
        $factory = new Provider\Factory($loader);
        $this->assertInstanceOf(Provider::class, $factory->create('file'));
    }
}
