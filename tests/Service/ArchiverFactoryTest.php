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
use App\Service\ArchiverFactory;
use App\Service\ZipArchiveFactory;
use App\Util\FinderFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

class ArchiverFactoryTest extends TestCase
{
    /**
     * @covers \App\Service\ArchiverFactory::create()
     * @covers \App\Service\ArchiverFactory::__construct()
     */
    public function testCreate()
    {
        $filesystem = $this->createMock(Filesystem::class);
        $finderFactory = $this->createMock(FinderFactory::class);
        $zipArchiveFactory = $this->createMock(ZipArchiveFactory::class);
        $factory = new ArchiverFactory($filesystem, $finderFactory, $zipArchiveFactory);
        $archiver = $factory->create('dummy/path');
        $this->assertInstanceOf(Archiver::class, $archiver);
    }
}
