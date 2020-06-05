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

namespace App\Umc\CoreBundle\Tests\Unit\Model\Attribute\Dynamic;

use App\Umc\CoreBundle\Model\Attribute;
use App\Umc\CoreBundle\Model\Attribute\Dynamic\Factory;
use App\Umc\CoreBundle\Model\Attribute\Dynamic\Option\Factory as OptionFactory;
use App\Umc\CoreBundle\Model\Attribute\Dynamic\Type\Factory as TypeFactory;
use App\Umc\CoreBundle\Util\StringUtil;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    /**
     * @var Attribute | MockObject
     */
    private $attribute;
    /**
     * @var Factory
     */
    private $factory;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $optionFactory = $this->createMock(OptionFactory::class);
        $stringUtil = $this->createMock(StringUtil::class);
        $typeFactory = $this->createMock(TypeFactory::class);
        $this->attribute = $this->createMock(Attribute::class);
        $this->factory = new Factory(
            $optionFactory,
            $stringUtil,
            $typeFactory
        );
    }

    /**
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic\Factory::create
     * @covers \App\Umc\CoreBundle\Model\Attribute\Dynamic\Factory::__construct
     */
    public function testCreate()
    {
        $this->assertInstanceOf(Attribute\Dynamic::class, $this->factory->create($this->attribute));
    }
}
