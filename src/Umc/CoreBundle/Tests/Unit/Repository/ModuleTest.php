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

namespace App\Umc\CoreBundle\Tests\Unit\Repository;

use App\Umc\CoreBundle\Config\Provider;
use App\Umc\CoreBundle\Config\Provider\Factory;
use App\Umc\CoreBundle\Model\Module;
use App\Umc\CoreBundle\Model\Platform;
use App\Umc\CoreBundle\Model\Platform\Version;
use App\Umc\CoreBundle\Repository\Module as ModuleRepository;
use App\Umc\CoreBundle\Service\FileFinderFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class ModuleTest extends TestCase
{
    /**
     * @var Filesystem | MockObject
     */
    private $filesystem;
    /**
     * @var Factory | MockObject
     */
    private $providerFactory;
    /**
     * @var FileFinderFactory | MockObject
     */
    private $finderFactory;
    /**
     * @var Module | MockObject
     */
    private $module;
    /**
     * @var Version | MockObject
     */
    private $version;
    /**
     * @var Platform | MockObject
     */
    private $platform;
    /**
     * @var ModuleRepository
     */
    private $moduleRepository;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->filesystem = $this->createMock(Filesystem::class);
        $this->providerFactory = $this->createMock(Factory::class);
        $this->finderFactory = $this->createMock(FileFinderFactory::class);
        $this->module = $this->createMock(Module::class);
        $this->version = $this->createMock(Version::class);
        $this->platform = $this->createMock(Platform::class);
        $this->version->method('getPlatform')->willReturn($this->platform);
        $this->moduleRepository = new ModuleRepository(
            $this->filesystem,
            $this->providerFactory,
            $this->finderFactory,
            'root'
        );
    }


    /**
     * @covers \App\Umc\CoreBundle\Repository\Module::save
     * @covers \App\Umc\CoreBundle\Repository\Module::getRoot
     * @covers \App\Umc\CoreBundle\Repository\Module::__construct
     */
    public function testSave()
    {
        $this->module->method('toArray')->willReturn(['module_data']);
        $this->platform->method('getCode')->willReturn('platform');
        $this->version->method('getCode')->willReturn('version');
        $this->module->method('getExtensionName')->willReturn('module');
        $content = [
            'meta' => [
                'platform' => 'platform',
                'version' => 'version',
            ],
            'module' => ['module_data']
        ];
        $this->filesystem->expects($this->once())->method('dumpFile')
            ->with(
                'root/platform/version/module.yml',
                Yaml::dump($content, 100, 2, Yaml::DUMP_EMPTY_ARRAY_AS_SEQUENCE)
            );
        $this->moduleRepository->save($this->module, $this->version);
    }

    /**
     * @covers \App\Umc\CoreBundle\Repository\Module::load
     * @covers \App\Umc\CoreBundle\Repository\Module::getRoot
     * @covers \App\Umc\CoreBundle\Repository\Module::__construct
     */
    public function testLoad()
    {
        $provider = $this->createMock(Provider::class);
        $provider->method('getConfig')->willReturn(['config']);
        $this->providerFactory->method('create')->willReturn($provider);
        $this->filesystem->method('exists')->willReturn(true);
        $this->assertEquals(['config'], $this->moduleRepository->load('module', $this->version));
    }

    /**
     * @covers \App\Umc\CoreBundle\Repository\Module::load
     * @covers \App\Umc\CoreBundle\Repository\Module::getRoot
     * @covers \App\Umc\CoreBundle\Repository\Module::__construct
     */
    public function testLoadWithError()
    {
        $this->providerFactory->expects($this->never())->method('create');
        $this->filesystem->method('exists')->willReturn(false);
        $this->expectException(\Exception::class);
        $this->moduleRepository->load('module', $this->version);
    }

    /**
     * @covers \App\Umc\CoreBundle\Repository\Module::getPlatformModules
     * @covers \App\Umc\CoreBundle\Repository\Module::getVersionModules
     * @covers \App\Umc\CoreBundle\Repository\Module::getRoot
     * @covers \App\Umc\CoreBundle\Repository\Module::__construct
     */
    public function testGetPlatformModules()
    {
        $finder1 = $this->getFinderMock();
        $finder1->method('getIterator')->willReturn([
            $this->getFileMock('file1.yml', '2020-01-01'),
            $this->getFileMock('file2.yml', '2021-01-01'),
        ]);
        $finder2 = $this->getFinderMock();
        $finder2->method('getIterator')->willReturn([
            $this->getFileMock('file3.yml', '2020-01-01'),
        ]);
        $this->finderFactory->expects($this->exactly(2))->method('create')
            ->willReturnOnConsecutiveCalls($finder1, $finder2);
        $version1 = $this->createMock(Version::class);
        $version1->method('getCode')->willReturn('code1');
        $version2 = $this->createMock(Version::class);
        $version2->method('getCode')->willReturn('code2');
        $this->platform->method('getVersions')->willReturn([$version1, $version2]);
        $this->assertEquals(2, count($this->moduleRepository->getPlatformModules($this->platform)));
    }

    /**
     * @covers \App\Umc\CoreBundle\Repository\Module::getVersionModules
     * @covers \App\Umc\CoreBundle\Repository\Module::getRoot
     * @covers \App\Umc\CoreBundle\Repository\Module::__construct
     */
    public function testGetVersionModules()
    {
        $finder = $this->getFinderMock();
        $finder->method('getIterator')->willReturn([
            $this->getFileMock('file1.yml', '2020-01-01'),
            $this->getFileMock('file2.yml', '2021-01-01'),
        ]);
        $this->finderFactory->method('create')->willReturn($finder);
        $result = $this->moduleRepository->getVersionModules($this->version);
        $this->assertEquals(2, count($result));
        $this->assertEquals('file1', $result[0]['module_name']);
        $this->assertEquals('file2.yml', $result[1]['name']);
    }

    /**
     * @covers \App\Umc\CoreBundle\Repository\Module::getVersionModules
     * @covers \App\Umc\CoreBundle\Repository\Module::getRoot
     * @covers \App\Umc\CoreBundle\Repository\Module::__construct
     */
    public function testGetVersionModulesWithException()
    {
        $finder = $this->getFinderMock();
        $finder->method('getIterator')->willThrowException($this->createMock(DirectoryNotFoundException::class));
        $this->finderFactory->method('create')->willReturn($finder);
        $this->assertEquals([], $this->moduleRepository->getVersionModules($this->version));
    }

    /**
     * @covers \App\Umc\CoreBundle\Repository\Module::getRoot
     * @covers \App\Umc\CoreBundle\Repository\Module::__construct
     */
    public function testGetRoot()
    {
        $this->platform->method('getCode')->willReturn('platform');
        $this->version->method('getCode')->willReturn('version');
        $this->assertEquals('root/platform/version', $this->moduleRepository->getRoot($this->version));
    }

    /**
     * @param $name
     * @param $time
     * @return MockObject|\SplFileInfo
     */
    private function getFileMock($name, $time)
    {
        $mock = $this->createMock(\SplFileInfo::class);
        $mock->method('getFilename')->willReturn($name);
        $mock->method('getMTime')->willReturn($time);
        return $mock;
    }

    /**
     * @return MockObject|Finder
     */
    private function getFinderMock()
    {
        $finder = $this->createMock(Finder::class);
        $finder->method('files')->willReturnSelf();
        $finder->method('in')->willReturnSelf();
        $finder->method('depth')->willReturnSelf();
        $finder->method('name')->willReturnSelf();
        $finder->method('sortByName')->willReturnSelf();
        return $finder;
    }
}
