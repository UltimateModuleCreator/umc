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

namespace App\Model\Attribute;

use App\Model\Attribute;

interface TypeInterface
{
    /**
     * @return Attribute
     */
    public function getAttribute() : Attribute;

    /**
     * @return string
     */
    public function renderGrid() : string;

    /**
     * @return string
     */
    public function renderForm() : string;

    /**
     * @param string $key
     * @param null|string $default
     * @return null|string
     */
    public function getData(string $key, ?string $default = null) : ?string;
}
