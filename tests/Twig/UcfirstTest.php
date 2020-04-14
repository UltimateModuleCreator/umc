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
namespace App\Tests\Twig;

use App\Twig\Ucfirst;
use App\Util\StringUtil;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Twig\TwigFilter;

class UcfirstTest extends TestCase
{
    /**
     * @var StringUtil | MockObject
     */
    private $stringUtil;

    /**
     * Setup tests
     */
    protected function setUp()
    {
        $this->stringUtil = $this->createMock(StringUtil::class);
    }
    /**
     * @covers \App\Twig\Ucfirst::ucfirst()
     * @covers \App\Twig\Ucfirst::__construct()
     */
    public function testUcfirst()
    {
        $snake = new Ucfirst($this->stringUtil);
        $this->stringUtil->expects($this->once())->method('ucfirst');
        $snake->ucfirst('some_text');
    }

    /**
     * @covers \App\Twig\Ucfirst::getFilters()
     */
    public function testGetFilters()
    {
        $ucfirst = new Ucfirst($this->stringUtil);
        $filters = $ucfirst->getFilters();
        $this->assertEquals(1, count($filters));
        $this->assertInstanceOf(TwigFilter::class, $filters[0]);
    }
}
