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

namespace App\Umc\MagentoBundle\Tests\Unit\Model\Module;

use App\Umc\CoreBundle\Model\Module;
use App\Umc\CoreBundle\Util\StringUtil;
use App\Umc\CoreBundle\Model\Entity\Factory as EntityFactory;
use App\Umc\MagentoBundle\Model\Module\Factory;
use App\Umc\CoreBundle\Model\Relation\Factory as RelationFactory;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    /**
     * @covers \App\Umc\MagentoBundle\Model\Module\Factory::create
     * @covers \App\Umc\MagentoBundle\Model\Module\Factory::__construct
     */
    public function testCreate()
    {
        $factory = new Factory(
            $this->createMock(StringUtil::class),
            $this->createMock(EntityFactory::class),
            $this->createMock(RelationFactory::class),
            ['menu']
        );
        $this->assertInstanceOf(Module::class, $factory->create(['data']));
    }
}
