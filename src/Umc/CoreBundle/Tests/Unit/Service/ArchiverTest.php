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

namespace App\Umc\CoreBundle\Tests\Unit\Service;

use App\Umc\CoreBundle\Service\Archiver;
use App\Umc\CoreBundle\Service\ZipArchiveFactory;
use App\Umc\CoreBundle\Service\FileFinderFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

class ArchiverTest extends TestCase
{
    /**
     * @var ZipArchiveFactory | MockObject
     */
    private $zipFactory;
    /**
     * @var \ZipArchive | MockObject $zip
     */
    private $zip;
    /**
     * @var FileFinderFactory | MockObject
     */
    private $finderFactory;
    /**
     * @var Finder | MockObject
     */
    private $finder;
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

        $this->finderFactory = $this->createMock(FileFinderFactory::class);
        $this->finder = $this->createMock(Finder::class);
        $this->finderFactory->expects($this->once())->method('create')->willReturn($this->finder);
        $this->finder->expects($this->once())->method('in')->willReturnSelf();

        $this->archiver = new Archiver($this->finderFactory, $this->zipFactory);
    }

    /**
     * @covers \App\Umc\CoreBundle\Service\Archiver::createZip()
     * @covers \App\Umc\CoreBundle\Service\Archiver::__construct()
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
        $this->archiver->createZip('someSource', 'zipName', true);
    }
}
