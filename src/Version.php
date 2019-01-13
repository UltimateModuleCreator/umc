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

namespace App;

class Version
{
    const VERSION = '3.0.0';
    const BUILD = 'beta1';

    /**
     * @return string
     */
    public static function getVersion() : string
    {
        return self::VERSION . ((self::BUILD) ? '-' . self::BUILD : '');
    }
}
