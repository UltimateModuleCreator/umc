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

use App\Twig\SnakeCase;
use App\Util\StringUtil;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Twig\TwigFilter;

class SnakeCaseTest extends TestCase
{
    /**
     * @var StringUtil | MockObject
     */
    private $stringUtil;

    /**
     * Setup tests
     */
    protected function setUp(): void
    {
        $this->stringUtil = $this->createMock(StringUtil::class);
    }

    /**
     * @covers \App\Twig\SnakeCase::snake()
     * @covers \App\Twig\SnakeCase::__construct()
     */
    public function testSnake()
    {
        $snake = new SnakeCase($this->stringUtil);
        $this->stringUtil->expects($this->once())->method('snake');
        $snake->snake('some_text');
    }

    /**
     * @covers \App\Twig\SnakeCase::getFilters()
     */
    public function testGetFilters()
    {
        $snake = new SnakeCase($this->stringUtil);
        $filters = $snake->getFilters();
        $this->assertEquals(1, count($filters));
        $this->assertInstanceOf(TwigFilter::class, $filters[0]);
    }
}
