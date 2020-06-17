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

namespace App\Umc\CoreBundle\Tests\Unit\Config;

use App\Umc\CoreBundle\Config\Loader;
use App\Umc\CoreBundle\Config\Modifier\ModifierInterface;
use App\Umc\CoreBundle\Config\ProcessorFactory;
use Symfony\Component\Config\Definition\Processor;
use App\Umc\CoreBundle\Config\Provider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class LoaderTest extends TestCase
{
    /**
     * @var ParameterBagInterface | MockObject
     */
    private $parameterBag;
    /**
     * @var ModifierInterface | MockObject
     */
    private $modifier;
    /**
     * @var ProcessorFactory | MockObject
     */
    private $processorFactory;
    /**
     * @var iterable | MockObject
     */
    private $providers;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->parameterBag = $this->createMock(ParameterBagInterface::class);
        $this->modifier = $this->createMock(ModifierInterface::class);
        $this->processorFactory = $this->createMock(ProcessorFactory::class);
    }

    /**
     * @covers \App\Umc\CoreBundle\Config\Loader::getConfig
     * @covers \App\Umc\CoreBundle\Config\Loader::addProvider
     * @covers \App\Umc\CoreBundle\Config\Loader::instantiateConfigClass
     * @covers \App\Umc\CoreBundle\Config\Loader::__construct
     */
    public function testGetConfig()
    {
        $class = $this->createMock(ConfigurationInterface::class);
        $loader= new Loader(
            $this->parameterBag,
            $this->modifier,
            $this->processorFactory,
            [$this->getProviderMock(), $this->getProviderMock()],
            get_class($class)
        );
        $this->modifier->expects($this->once())->method('modify')->willReturnArgument(0);
        $processor = $this->createMock(Processor::class);
        $this->processorFactory->expects($this->once())->method('create')->willReturn($processor);
        $processor->method('processConfiguration')->willReturn(['processed']);
        $this->assertEquals(['processed'], $loader->getConfig());
        //call twice to test memoizing
        $this->assertEquals(['processed'], $loader->getConfig());
    }

    /**
     * @covers \App\Umc\CoreBundle\Config\Loader::getConfig
     * @covers \App\Umc\CoreBundle\Config\Loader::addProvider
     * @covers \App\Umc\CoreBundle\Config\Loader::instantiateConfigClass
     * @covers \App\Umc\CoreBundle\Config\Loader::__construct
     */
    public function testGetConfigWrongClass()
    {
        $this->expectException(\InvalidArgumentException::class);
        $loader= new Loader(
            $this->parameterBag,
            $this->modifier,
            $this->processorFactory,
            [$this->getProviderMock(), $this->getProviderMock()],
            '\stdClass'
        );
        $loader->getConfig();
    }

    /**
     * @return MockObject
     */
    private function getProviderMock()
    {
        $mock = $this->createMock(Provider::class);
        $mock->method('getConfig')->willReturn([]);
        return $mock;
    }
}
