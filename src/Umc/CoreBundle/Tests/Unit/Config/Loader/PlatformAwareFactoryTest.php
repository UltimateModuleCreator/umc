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

namespace App\Umc\CoreBundle\Tests\Unit\Config\Loader;

use App\Umc\CoreBundle\Config\Loader;
use App\Umc\CoreBundle\Config\Loader\PlatformAwareFactory;
use App\Umc\CoreBundle\Config\Modifier\ModifierInterface;
use App\Umc\CoreBundle\Config\ProcessorFactory;
use App\Umc\CoreBundle\Config\Provider;
use App\Umc\CoreBundle\Config\Provider\Factory;
use App\Umc\CoreBundle\Model\Platform;
use App\Umc\CoreBundle\Model\Platform\Version;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PlatformAwareFactoryTest extends TestCase
{
    /**
     * @var Factory | MockObject
     */
    private $providerFactory;
    /**
     * @var Version | MockObject
     */
    private $version;
    /**
     * @var Platform | MockObject
     */
    private $platform;
    /**
     * @var PlatformAwareFactory
     */
    private $platformAwareFactory;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->providerFactory = $this->createMock(Factory::class);
        $parameterBag = $this->createMock(ParameterBagInterface::class);
        $modifier = $this->createMock(ModifierInterface::class);
        $processorFactory = $this->createMock(ProcessorFactory::class);
        $this->version = $this->createMock(Version::class);
        $this->platform = $this->createMock(Platform::class);
        $this->platformAwareFactory = new PlatformAwareFactory(
            $this->providerFactory,
            $parameterBag,
            $modifier,
            $processorFactory,
            'config',
            'className'
        );
    }

    /**
     * @covers \App\Umc\CoreBundle\Config\Loader\PlatformAwareFactory::createByVersion
     * @covers \App\Umc\CoreBundle\Config\Loader\PlatformAwareFactory::create
     * @covers \App\Umc\CoreBundle\Config\Loader\PlatformAwareFactory::__construct
     */
    public function testCreateByVersion()
    {
        $this->version->expects($this->once())->method('getConfig')->willReturn(['file1', 'file1']);
        $this->providerFactory->expects($this->exactly(2))
            ->method('create')
            ->willReturn($this->createMock(Provider::class));
        $this->assertInstanceOf(Loader::class, $this->platformAwareFactory->createByVersion($this->version));
    }

    /**
     * @covers \App\Umc\CoreBundle\Config\Loader\PlatformAwareFactory::createByPlatform
     * @covers \App\Umc\CoreBundle\Config\Loader\PlatformAwareFactory::create
     * @covers \App\Umc\CoreBundle\Config\Loader\PlatformAwareFactory::__construct
     */
    public function testCreateByPlatform()
    {
        $this->platform->expects($this->once())->method('getConfig')->willReturn(['file1', 'file1']);
        $this->providerFactory->expects($this->exactly(2))
            ->method('create')
            ->willReturn($this->createMock(Provider::class));
        $this->assertInstanceOf(Loader::class, $this->platformAwareFactory->createByPlatform($this->platform));
    }
}
