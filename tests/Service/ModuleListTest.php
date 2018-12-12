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
 */
declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\ModuleList;
use App\Util\FinderFactory;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class ModuleListTest extends TestCase
{
    /**
     * @var FinderFactory | MockObject
     */
    private $finderFactory;
    /**
     * @var Finder | MockObject
     */
    private $finder;
    /**
     * @var Filesystem | MockObject
     */
    private $filesystem;

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->finder = $this->createMock(Finder::class);
        $this->finderFactory = $this->createMock(FinderFactory::class);
        $this->filesystem = $this->createMock(Filesystem::class);
        $this->finderFactory->method('create')->willReturn($this->finder);
    }

    /**
     * @covers \App\Service\ModuleList::getModules()
     * @covers \App\Service\ModuleList::createBaseDir()
     * @covers \App\Service\ModuleList::__construct()
     */
    public function testGetModules()
    {
        $this->finder->expects($this->once())->method('getIterator')->willReturn([
            $this->getFileMock('File1'),
            $this->getFileMock('File2'),
            $this->getFileMock('File3')
        ]);

        $this->filesystem->method('exists')->willReturn(false);
        $this->filesystem->expects($this->once())->method('mkdir');
        $this->finder->expects($this->once())->method('files')->willReturnSelf();
        $this->finder->expects($this->once())->method('in')->willReturnSelf();
        $this->finder->expects($this->once())->method('name')->willReturnSelf();
        $this->finder->expects($this->once())->method('sortByName')->willReturnSelf();
        $this->finder->expects($this->once())->method('depth')->willReturnSelf();

        $moduleList = new ModuleList($this->finderFactory, $this->filesystem, 'path/to/modules');
        $moduleList->getModules();
        //call twice to test memoizing
        $moduleList->getModules();
    }

    /**
     * @return MockObject
     */
    private function getFileMock($filename)
    {
        $mock = $this->createMock(SplFileInfo::class);
        $mock->method('getFilename')->willReturn($filename);
        return $mock;
    }
}
