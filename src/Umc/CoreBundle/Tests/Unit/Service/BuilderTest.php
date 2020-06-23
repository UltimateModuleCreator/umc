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

use App\Umc\CoreBundle\Config\Loader;
use App\Umc\CoreBundle\Config\Loader\PlatformAwareFactory;
use App\Umc\CoreBundle\Model\Module\Factory\Locator as FactoryLocator;
use App\Umc\CoreBundle\Model\Platform\Version;
use App\Umc\CoreBundle\Repository\Module;
use App\Umc\CoreBundle\Service\Archiver;
use App\Umc\CoreBundle\Service\Builder;
use App\Umc\CoreBundle\Service\Cs\Executor;
use App\Umc\CoreBundle\Service\Generator\Pool\Locator;
use App\Umc\CoreBundle\Service\Validator\Locator as ValidatorLocator;
use App\Umc\CoreBundle\Service\Validator\Pool;
use App\Umc\CoreBundle\Service\Validator\ValidationException;
use App\Umc\CoreBundle\Model\Module\Factory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

class BuilderTest extends TestCase
{
    /**
     * @var FactoryLocator | MockObject
     */
    private $factoryLocator;
    /**
     * @var Factory | MockObject
     */
    private $factory;
    /**
     * @var Locator | MockObject
     */
    private $generatorPoolLocator;
    /**
     * @var \App\Umc\CoreBundle\Service\Generator\Pool | MockObject
     */
    private $generatorPool;
    /**
     * @var Module | MockObject
     */
    private $repository;
    /**
     * @var PlatformAwareFactory | MockObject
     */
    private $configLoaderFactory;
    /**
     * @var Filesystem | MockObject
     */
    private $filesystem;
    /**
     * @var Archiver | MockObject
     */
    private $archiver;
    /**
     * @var Executor | MockObject
     */
    private $executor;
    /**
     * @var ValidatorLocator | MockObject
     */
    private $validatorLocator;
    /**
     * @var Version | MockObject
     */
    private $version;
    /**
     * @var Builder
     */
    private $builder;
    /**
     * @var Pool
     */
    private $validatorPool;
    /**
     * @var \App\Umc\CoreBundle\Model\Module
     */
    private $module;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->factoryLocator = $this->createMock(FactoryLocator::class);
        $this->factory = $this->createMock(Factory::class);
        $this->factoryLocator->method('getFactory')->willReturn($this->factory);
        $this->generatorPoolLocator = $this->createMock(Locator::class);
        $this->generatorPool = $this->createMock(\App\Umc\CoreBundle\Service\Generator\Pool::class);
        $this->generatorPoolLocator->method('getGeneratorPool')->willReturn($this->generatorPool);
        $this->repository = $this->createMock(Module::class);
        $this->configLoaderFactory = $this->createMock(PlatformAwareFactory::class);
        $this->filesystem = $this->createMock(Filesystem::class);
        $this->archiver = $this->createMock(Archiver::class);
        $this->executor = $this->createMock(Executor::class);
        $this->validatorLocator = $this->createMock(ValidatorLocator::class);
        $this->version = $this->createMock(Version::class);
        $this->validatorPool = $this->createMock(Pool::class);
        $this->validatorLocator->method('getValidatorPool')->willReturn($this->validatorPool);
        $this->module = $this->createMock(\App\Umc\CoreBundle\Model\Module::class);
        $this->factory->method('create')->willReturn($this->module);
        $this->builder = new Builder(
            $this->factoryLocator,
            $this->generatorPoolLocator,
            $this->repository,
            $this->configLoaderFactory,
            $this->filesystem,
            $this->archiver,
            $this->executor,
            $this->validatorLocator
        );
    }

    /**
     * @covers \App\Umc\CoreBundle\Service\Builder::build
     * @covers \App\Umc\CoreBundle\Service\Builder::writeFiles
     * @covers \App\Umc\CoreBundle\Service\Builder::__construct
     */
    public function testBuild()
    {
        $configLoader = $this->createMock(Loader::class);
        $this->configLoaderFactory->method('createByVersion')->willReturn($configLoader);
        $configLoader->method('getConfig')->willReturn([
            ['scope' => 'module'],
            ['scope' => 'entity'],
        ]);
        $this->executor->method('run')->willReturn(['file1', 'file2']);
        $this->validatorPool->method('validate')->with($this->module)->willReturn([]);
        $this->archiver->expects($this->once())->method('createZip');
        $this->filesystem->expects($this->once())->method('remove');
        $this->assertEquals($this->module, $this->builder->build($this->version, []));
    }

    /**
     * @covers \App\Umc\CoreBundle\Service\Builder::build
     * @covers \App\Umc\CoreBundle\Service\Builder::__construct
     */
    public function testBuildNotValid()
    {
        $this->expectException(ValidationException::class);
        $this->validatorPool->method('validate')->with($this->module)->willReturn(['error']);
        $this->archiver->expects($this->never())->method('createZip');
        $this->filesystem->expects($this->never())->method('remove');
        $this->assertEquals($this->module, $this->builder->build($this->version, []));
    }
}
