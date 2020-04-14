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

namespace App\Tests\Util;

use App\Util\FinderFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

class FinderFactoryTest extends TestCase
{
    /**
     * check if not the same instance of Finder is returned
     * @covers \App\Util\FinderFactory::create()
     */
    public function testCreate()
    {
        $factory = new FinderFactory();
        $finder1 = $factory->create();
        $finder2 = $factory->create();
        $this->assertNotSame($finder1, $finder2);
        $this->assertInstanceOf(Finder::class, $finder1);
        $this->assertInstanceOf(Finder::class, $finder1);
    }
}
