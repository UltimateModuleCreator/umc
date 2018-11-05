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
use App\Service\WriterFactory;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\Filesystem\Filesystem;

class WriterFactoryTest extends TestCase
{
    /**
     * @covers \App\Service\WriterFactory::create()
     * @covers \App\Service\WriterFactory::__construct()
     */
    public function testCreate()
    {
        $filesystem = $this->createMock(Filesystem::class);
        $factory = new WriterFactory($filesystem);
        $writer = $factory->create('dummy/path');
        $this->assertInstanceOf(Writer::class, $writer);
    }
}
