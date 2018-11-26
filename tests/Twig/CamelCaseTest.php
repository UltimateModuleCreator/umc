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

use App\Twig\CamelCase;
use App\Util\StringUtil;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Twig\TwigFilter;

class CamelCaseTest extends TestCase
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
     * @covers \App\Twig\CamelCase::camel()
     * @covers \App\Twig\CamelCase::__construct()
     */
    public function testCamel()
    {
        $camel = new CamelCase($this->stringUtil);
        $this->stringUtil->expects($this->once())->method('camel');
        $camel->camel('some_text');
    }

    /**
     * @covers \App\Twig\CamelCase::getFilters()
     */
    public function testGetFilters()
    {
        $camel = new CamelCase($this->stringUtil);
        $filters = $camel->getFilters();
        $this->assertEquals(1, count($filters));
        $this->assertInstanceOf(TwigFilter::class, $filters[0]);
    }
}
