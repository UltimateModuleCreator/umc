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

namespace App\Umc\CoreBundle\Tests\Unit\Model\Module;

use App\Umc\CoreBundle\Model\Entity\Factory as EntityFactory;
use App\Umc\CoreBundle\Model\Module;
use App\Umc\CoreBundle\Model\Module\Factory;
use App\Umc\CoreBundle\Util\StringUtil;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    /**
     * @var Factory
     */
    private $factory;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $stringUtil = $this->createMock(StringUtil::class);
        $entityFactory = $this->createMock(EntityFactory::class);
        $this->factory = new Factory(
            $stringUtil,
            $entityFactory
        );
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Module\Factory::create
     * @covers \App\Umc\CoreBundle\Model\Module\Factory::__construct
     */
    public function testCreate()
    {
        $this->assertInstanceOf(Module::class, $this->factory->create());
        $this->assertInstanceOf(Module::class, $this->factory->create(['data']));
    }
}
