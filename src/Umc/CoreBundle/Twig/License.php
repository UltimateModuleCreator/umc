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

use App\Umc\CoreBundle\Service\License\Pool;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class License extends AbstractExtension
{
    /**
     * @var Pool
     */
    private $pool;

    /**
     * License constructor.
     * @param Pool $pool
     */
    public function __construct(Pool $pool)
    {
        $this->pool = $pool;
    }

    /**
     * @return array|TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction(
                'formatLicense',
                function ($license, $type) {
                    return $this->pool->process($license, $type);
                }
            )
        ];
    }
}
