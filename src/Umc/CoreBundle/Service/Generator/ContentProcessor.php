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

namespace App\Umc\CoreBundle\Service\Generator;

class ContentProcessor
{
    public const SORT_START_MARKUP = '##sort##';
    public const SORT_END_MARKUP = '##/sort##';
    public const SORT_PATTERN = '/##sort##(.*?)##\\/sort##/si';
    /**
     * @param string $content
     * @return string
     */
    public function process(string $content): string
    {
        $content = $this->replaceEol($content);
        $content = $this->sortLines($content);
        return $content;
    }

    /**
     * @return string
     */
    public function getSortStartMarkup(): string
    {
        return self::SORT_START_MARKUP;
    }

    /**
     * @return string
     */
    public function getSortEndMarkup(): string
    {
        return self::SORT_END_MARKUP;
    }

    /**
     * @param string $content
     * @return string
     */
    private function replaceEol(string $content): string
    {
        return str_replace("\n\r", PHP_EOL, $content);
    }

    /**
     * @param string $content
     * @return string
     */
    private function sortLines(string $content): string
    {
        preg_match_all(self::SORT_PATTERN, $content, $sorts, PREG_SET_ORDER);
        if (preg_match_all(self::SORT_PATTERN, $content, $sorts, PREG_SET_ORDER)) {
            foreach ($sorts as $sort) {
                $content = str_replace($sort[0], $this->sortBlock($sort[1]), $content);
            }
        }
        return $content;
    }

    /**
     * @param string $content
     * @return string
     */
    private function sortBlock(string $content): string
    {
        $content = trim($content);
        $lines = explode(PHP_EOL, $content);
        sort($lines);
        return implode(PHP_EOL, $lines);
    }
}
