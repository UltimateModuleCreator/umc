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
 *
 */

declare(strict_types=1);

namespace App\Umc\CoreBundle\Tests\Unit\Twig;

use App\Umc\CoreBundle\Twig\Modifiers;
use App\Umc\CoreBundle\Util\StringUtil;
use App\Umc\CoreBundle\Version;
use PHPUnit\Framework\TestCase;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ModifiersTest extends TestCase
{
    /**
     * @var Modifiers
     */
    private $modifiers;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $stringUtil = $this->createMock(StringUtil::class);
        $this->modifiers = new Modifiers($stringUtil);
    }

    /**
     * @covers \App\Umc\CoreBundle\Twig\Modifiers::getFilters
     * @covers \App\Umc\CoreBundle\Twig\Modifiers::__construct
     */
    public function testGetFilters()
    {
        $filters = $this->modifiers->getFilters();
        $this->assertEquals(5, count($filters));
        $this->assertInstanceOf(TwigFilter::class, $filters[0]);
    }

    /**
     * @covers \App\Umc\CoreBundle\Twig\Modifiers::getFunctions
     * @covers \App\Umc\CoreBundle\Twig\Modifiers::__construct
     */
    public function testGetFunctions()
    {
        $functions = $this->modifiers->getFunctions();
        $this->assertEquals(1, count($functions));
        $this->assertInstanceOf(TwigFunction::class, $functions[0]);
        $this->assertEquals(Version::getVersion(), $functions[0]->getCallable()());
    }
}
