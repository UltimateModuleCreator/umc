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

use App\Umc\CoreBundle\Config\Modifier\Parameter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ParameterTest extends TestCase
{
    /**
     * @covers \App\Umc\CoreBundle\Config\Modifier\Parameter::modify
     * @covers \App\Umc\CoreBundle\Config\Modifier\Parameter::__construct
     */
    public function testModify()
    {
        $parameterBag = $this->createMock(ParameterBagInterface::class);
        $parameterBag->expects($this->once())->method('resolveValue')->with(['config'])->willReturn(['resolved']);
        $parameter = new Parameter($parameterBag);
        $this->assertEquals(['resolved'], $parameter->modify(['config']));
    }
}
