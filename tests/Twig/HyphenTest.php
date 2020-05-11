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

namespace App\Tests\Twig;

use App\Twig\Hyphen;
use App\Util\StringUtil;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Twig\TwigFilter;

class HyphenTest extends TestCase
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
     * @covers \App\Twig\Hyphen::hyphen()
     * @covers \App\Twig\Hyphen::__construct()
     */
    public function testHyphen()
    {
        $snake = new Hyphen($this->stringUtil);
        $this->stringUtil->expects($this->once())->method('hyphen');
        $snake->hyphen('some_text');
    }

    /**
     * @covers \App\Twig\Hyphen::getFilters()
     */
    public function testGetFilters()
    {
        $hyphen = new Hyphen($this->stringUtil);
        $filters = $hyphen->getFilters();
        $this->assertEquals(1, count($filters));
        $this->assertInstanceOf(TwigFilter::class, $filters[0]);
    }
}
