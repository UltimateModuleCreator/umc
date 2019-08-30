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
namespace App\Tests\Model;

use App\Model\Section;
use PHPUnit\Framework\TestCase;

class SectionTest extends TestCase
{
    /**
     * @covers \App\Model\Section::getLabel
     * @covers \App\Model\Section::__construct
     */
    public function testGetLabel()
    {
        $section = new Section('label', 'code', []);
        $this->assertEquals('label', $section->getLabel());
    }

    /**
     * @covers \App\Model\Section::getCode
     * @covers \App\Model\Section::__construct
     */
    public function testGetCode()
    {
        $section = new Section('label', 'code', []);
        $this->assertEquals('code', $section->getCode());
    }

    /**
     * @covers \App\Model\Section::getDependencies
     * @covers \App\Model\Section::__construct
     */
    public function testGetDependencies()
    {
        $section = new Section('label', 'code', ['d1', 'd2']);
        $this->assertEquals(['d1', 'd2'], $section->getDependencies());
    }

    /**
     * @covers \App\Model\Section::getDependencies
     * @covers \App\Model\Section::__construct
     */
    public function testGetDependenciesDefault()
    {
        $section = new Section('label', 'code');
        $this->assertEquals([], $section->getDependencies());
    }
}
