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

namespace App\Umc\CoreBundle\Tests\Unit\Service\Generator;

use App\Umc\CoreBundle\Service\Generator\ContentProcessor;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ContentProcessorTest extends TestCase
{
    /**
     * @covers \App\Umc\CoreBundle\Service\Generator\ContentProcessor::process
     * @covers \App\Umc\CoreBundle\Service\Generator\ContentProcessor::replaceEol
     * @covers \App\Umc\CoreBundle\Service\Generator\ContentProcessor::sortBlock
     * @covers \App\Umc\CoreBundle\Service\Generator\ContentProcessor::sortLines
     */
    public function testProcess()
    {
        $lines = [
            'pre-text',
            '##sort##',
            'line 3',
            'line 1',
            'line 2',
            '##/sort##',
            'after'
        ];
        $text = implode(PHP_EOL, $lines);
        $expected = [
            'pre-text',
            'line 1',
            'line 2',
            'line 3',
            'after'
        ];
        $processor = new ContentProcessor();
        $this->assertEquals(implode(PHP_EOL, $expected), $processor->process($text));
    }

    /**
     * @covers \App\Umc\CoreBundle\Service\Generator\ContentProcessor::getSortStartMarkup
     */
    public function testGetSortStartMarkup()
    {
        $processor = new ContentProcessor();
        $this->assertEquals('##sort##', $processor->getSortStartMarkup());
    }

    /**
     * @covers \App\Umc\CoreBundle\Service\Generator\ContentProcessor::getSortEndMarkup
     */
    public function testGetSortEndMarkup()
    {
        $processor = new ContentProcessor();
        $this->assertEquals('##/sort##', $processor->getSortEndMarkup());
    }
}
