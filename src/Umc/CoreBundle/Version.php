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

namespace App\Umc\CoreBundle;

class Version
{
    public const VERSION = '4.4.0';
    public const BUILD = '';

    /**
     * @return string
     */
    public static function getVersion(): string
    {
        return self::VERSION . ((self::BUILD) ? '-' . self::BUILD : '');
    }
}
