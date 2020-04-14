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

use App\Service\Writer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

class WriterTest extends TestCase
{
    /**
     * @var Filesystem | MockObject
     */
    private $filesystem;
    /**
     * @var Writer
     */
    private $writer;

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->filesystem = $this->createMock(Filesystem::class);
        $this->writer = new Writer('dummy/path', $this->filesystem);
    }

    /**
     * @covers \App\Service\Writer::writeFiles
     * @covers \App\Service\Writer::writeFile
     * @covers \App\Service\Writer::__construct()
     */
    public function testWriteFiles()
    {
        $files = ['file1' => 'content1', 'file2' => 'content2', 'file3' => 'content3'];
        $this->filesystem->expects($this->exactly(4))
            ->method('exists')
            ->willReturnOnConsecutiveCalls(true, true, false, true);
        $this->filesystem->expects($this->exactly(1))->method('mkdir');
        $this->filesystem->expects($this->exactly(3))->method('dumpFile');
        $this->writer->writeFiles($files, 'dummy_dir');
    }

    /**
     * @covers \App\Service\Writer::writeFiles
     */
    public function testWriteFilesWithNoBaseDir()
    {
        $files = [];
        $this->filesystem->expects($this->exactly(1))
            ->method('exists')->willReturn(false);
        $this->filesystem->expects($this->exactly(1))->method('mkdir');
        $this->writer->writeFiles($files, 'dummy_dir');
    }
}
