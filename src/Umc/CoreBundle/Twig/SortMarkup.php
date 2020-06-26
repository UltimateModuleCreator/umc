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

namespace App\Umc\CoreBundle\Twig;

use App\Umc\CoreBundle\Service\Generator\ContentProcessor;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SortMarkup extends AbstractExtension
{
    /**
     * @var ContentProcessor
     */
    private $contentProcessor;

    /**
     * SortMarkup constructor.
     * @param ContentProcessor $contentProcessor
     */
    public function __construct(ContentProcessor $contentProcessor)
    {
        $this->contentProcessor = $contentProcessor;
    }

    /**
     * @return array|TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction(
                'sortStart',
                function () {
                    return $this->contentProcessor->getSortStartMarkup();
                }
            ),
            new TwigFunction(
                'sortEnd',
                function () {
                    return $this->contentProcessor->getSortEndMarkup();
                }
            ),
        ];
    }
}
