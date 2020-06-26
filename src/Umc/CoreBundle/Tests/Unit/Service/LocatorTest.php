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

namespace App\Umc\CoreBundle\Tests\Unit\Service;

use App\Umc\CoreBundle\Service\Locator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LocatorTest extends TestCase
{
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var Locator
     */
    private $locator;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->container = $this->createMock(ContainerInterface::class);
        $this->locator = new Locator($this->container);
    }

    /**
     * @covers \App\Umc\CoreBundle\Service\Locator::getService
     * @covers \App\Umc\CoreBundle\Service\Locator::__construct
     */
    public function testGetService()
    {
        $this->container->expects($this->once())->method('get')->willReturn('service');
        $this->assertEquals('service', $this->locator->getService('id'));
    }
}
