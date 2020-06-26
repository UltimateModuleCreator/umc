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

use App\Umc\CoreBundle\Service\Generator\ContentProcessor;
use App\Umc\CoreBundle\Twig\SortMarkup;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SortMarkupTest extends TestCase
{
    /**
     * @var ContentProcessor | MockObject
     */
    private $contentProcessor;
    /**
     * @var SortMarkup
     */
    private $sortMarkup;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->contentProcessor = $this->createMock(ContentProcessor::class);
        $this->sortMarkup = new SortMarkup($this->contentProcessor);
    }

    /**
     * @covers \App\Umc\CoreBundle\Twig\SortMarkup::getFunctions
     * @covers \App\Umc\CoreBundle\Twig\SortMarkup::__construct
     */
    public function testGetFunctions()
    {
        $this->contentProcessor->method('getSortStartMarkup')->willReturn('start');
        $this->contentProcessor->method('getSortEndMarkup')->willReturn('end');
        $result = $this->sortMarkup->getFunctions();
        $this->assertEquals(2, count($result));
        $this->assertEquals('start', $result[0]->getCallable()());
        $this->assertEquals('end', $result[1]->getCallable()());
    }
}
