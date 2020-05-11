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

namespace App\Tests\Util;

use App\Util\CodingStandards;
use App\Util\CodingStandardsFactory;
use App\Util\ProcessFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CodingStandardsFactoryTest extends TestCase
{
    /**
     * @covers \App\Util\CodingStandardsFactory::create()
     * @covers \App\Util\CodingStandardsFactory::__construct()
     */
    public function testCreate()
    {
        /** @var ProcessFactory | MockObject $processFactory */
        $processFactory = $this->createMock(ProcessFactory::class);
        $standards = ['standard1', 'standard2'];
        $phpCsPath = 'dummPath';
        $factory = new CodingStandardsFactory($processFactory, $standards, $phpCsPath);
        $this->assertInstanceOf(CodingStandards::class, $factory->create('dummyPath'));
    }
}
