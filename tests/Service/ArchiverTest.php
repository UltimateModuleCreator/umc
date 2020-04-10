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

namespace App\Tests\Service;

use App\Service\Archiver;
use App\Service\ZipArchiveFactory;
use App\Util\FinderFactory;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class ArchiverTest extends TestCase
{
    /**
     * @var ZipArchiveFactory | MockObject $zipFactory
     */
    private $zipFactory;
    /**
     * @var \ZipArchive | MockObject $zip
     */
    private $zip;
    /**
     * @var FinderFactory | MockObject $finderFactory
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
     * @var Archiver
     */
    private $archiver;

    /**
     * setup tests
     */
    protected function setUp(): void
    {

        $this->zipFactory = $this->createMock(ZipArchiveFactory::class);
        $this->zip = $this->createMock(\ZipArchive::class);
        $this->zipFactory->expects($this->once())->method('create')->willReturn($this->zip);
        $this->zip->expects($this->once())->method('open');

        $this->finderFactory = $this->createMock(FinderFactory::class);
        $this->finder = $this->createMock(Finder::class);
        $this->finderFactory->expects($this->once())->method('create')->willReturn($this->finder);
        $this->finder->expects($this->once())->method('in')->willReturnSelf();

        $this->filesystem = $this->createMock(Filesystem::class);
        $this->archiver = new Archiver($this->filesystem, 'dummy/path', $this->finderFactory, $this->zipFactory);
    }

    /**
     * @covers \App\Service\Archiver::createZip()
     * @covers \App\Service\Archiver::__construct()
     */
    public function testCreateZip()
    {
        $file1 = $this->createMock(\SplFileInfo::class);
        $file1->expects($this->once())->method('isDir')->willReturn(false);
        $file1->expects($this->once())->method('getRealPath')->willReturn('some/path/file1');

        $file2 = $this->createMock(\SplFileInfo::class);
        $file2->expects($this->once())->method('isDir')->willReturn(true);
        $file2->expects($this->never())->method('getRealPath');

        $this->finder->method('getIterator')->willReturn([$file1, $file2]);
        $this->filesystem->expects($this->once())->method('remove');
        $this->archiver->createZip('someSource', 'zipName', true);
    }

    /**
     * @covers \App\Service\Archiver::createZip()
     */
    public function testCreateZipNoRemove()
    {
        $this->finder->method('getIterator')->willReturn([]);
        $this->filesystem->expects($this->never())->method('remove');
        $this->archiver->createZip('someSource', 'zipName', false);
    }
}
