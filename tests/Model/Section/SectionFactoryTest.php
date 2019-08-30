<?php
/**
 *
 * UMC
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 *  that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 *
 * @copyright Marius Strajeru
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 * @author    Marius Strajeru <ultimate.module.creator@gmail.com>
 *
 */
namespace App\Tests\Model\Section;

use App\Model\Section;
use App\Model\Section\SectionFactory;
use PHPUnit\Framework\TestCase;

class SectionFactoryTest extends TestCase
{
    /**
     * @covers \App\Model\Section\SectionFactory::create
     */
    public function testCreate()
    {
        $factory = new SectionFactory();
        $this->assertInstanceOf(Section::class, $factory->create('label', 'code', []));
    }
}
