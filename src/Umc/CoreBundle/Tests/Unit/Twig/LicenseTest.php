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

namespace App\Umc\CoreBundle\Tests\Unit\Twig;

use App\Umc\CoreBundle\Model\Module;
use App\Umc\CoreBundle\Service\License\Pool;
use PHPUnit\Framework\TestCase;
use Twig\TwigFunction;

class LicenseTest extends TestCase
{
    /**
     * @covers \App\Umc\CoreBundle\Twig\License::getFunctions
     * @covers \App\Umc\CoreBundle\Twig\License::__construct
     */
    public function testGetFunctions()
    {
        $pool = $this->createMock(Pool::class);
        $pool->expects($this->once())->method('process')->willReturn('processed');
        $module = $this->createMock(Module::class);
        $license = new \App\Umc\CoreBundle\Twig\License($pool);
        $result = $license->getFunctions();
        $this->assertEquals(1, count($result));
        $this->assertInstanceOf(TwigFunction::class, $result[0]);
        $this->assertEquals('processed', $result[0]->getCallable()($module, 'xml'));
    }
}
