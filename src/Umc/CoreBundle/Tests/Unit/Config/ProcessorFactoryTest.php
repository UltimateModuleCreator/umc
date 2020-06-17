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

use App\Umc\CoreBundle\Config\ProcessorFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

class ProcessorFactoryTest extends TestCase
{
    /**
     * @covers \App\Umc\CoreBundle\Config\ProcessorFactory::create
     */
    public function testCreate()
    {
        $factory = new ProcessorFactory();
        $this->assertInstanceOf(Processor::class, $factory->create());
    }
}
