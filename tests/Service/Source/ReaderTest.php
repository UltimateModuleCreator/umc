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

namespace App\Tests\Service\Source;

use App\Service\Source\Reader;
use App\Util\YamlLoader;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class ReaderTest extends TestCase
{
    /**
     * @var YamlLoader | MockObject
     */
    private $yamlLoader;
    /**
     * @var Reader
     */
    private $reader;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->yamlLoader = $this->createMock(YamlLoader::class);
        $this->reader = new Reader($this->yamlLoader, ['source.yml']);
    }

    /**
     * @covers \App\Service\Source\Reader::getFiles()
     * @covers \App\Service\Source\Reader::__construct()
     * @covers \App\Service\Source\Reader::loadSource()
     */
    public function testGetFiles()
    {
        $expected = [
            'file1' => [],
            'file2' => []
        ];
        $this->yamlLoader->expects($this->once())->method('load')->willReturn($expected);
        //call twice to test memoizing
        $this->reader->getFiles();
        $result = $this->reader->getFiles();
        $this->assertEquals($expected, $result);
    }

    /**
     * @covers \App\Service\Source\Reader::getFiles()
     */
    public function testGetFilesWithException()
    {
        $exception  = new \Exception('Error');
        $this->yamlLoader->expects($this->once())->method('load')->willThrowException($exception);
        $this->expectException(\Exception::class);
        $this->reader->getFiles();
    }
}
