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

namespace App\Umc\CoreBundle\Tests\Unit\Service\Cs;

use App\Umc\CoreBundle\Service\Cs\ProcessFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

class ProcessFactoryTest extends TestCase
{
    /**
     * @covers \App\Umc\CoreBundle\Service\Cs\ProcessFactory::create
     */
    public function testCreate()
    {
        $factory = new ProcessFactory();
        $this->assertInstanceOf(Process::class, $factory->create(['dummy']));
    }
}
