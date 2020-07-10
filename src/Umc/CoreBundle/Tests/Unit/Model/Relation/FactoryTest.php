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

namespace App\Umc\CoreBundle\Tests\Unit\Model\Relation;

use App\Umc\CoreBundle\Model\Module;
use App\Umc\CoreBundle\Model\Relation;
use App\Umc\CoreBundle\Util\StringUtil;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    /**
     * @covers \App\Umc\CoreBundle\Model\Relation\Factory::create
     * @covers \App\Umc\CoreBundle\Model\Relation\Factory::__construct
     */
    public function testCreate()
    {
        $stringUtil = $this->createMock(StringUtil::class);
        /** @var Module | MockObject $module */
        $module = $this->createMock(Module::class);
        $factory = new Relation\Factory($stringUtil);
        $this->assertInstanceOf(Relation::class, $factory->create($module, []));
    }
}
