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

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use App\Umc\CoreBundle\Util\StringUtil;
use Twig\TwigFunction;

class Modifiers extends AbstractExtension
{
    /**
     * @var StringUtil
     */
    private $stringUtils;

    /**
     * Modifiers constructor.
     * @param StringUtil $stringUtils
     */
    public function __construct(StringUtil $stringUtils)
    {
        $this->stringUtils = $stringUtils;
    }

    /**
     * @return array|TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('snake', [$this->stringUtils, 'snake']),
            new TwigFilter('camel', [$this->stringUtils, 'camel']),
            new TwigFilter('hyphen', [$this->stringUtils, 'hyphen']),
            new TwigFilter('ucfirst', [$this->stringUtils, 'ucfirst']),
        ];
    }

    /**
     * @return array|TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'appVersion',
                function () {
                    return \App\Version::getVersion();
                }
            )
        ];
    }
}
