<?php
/**
 *
 * UMC
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 *  that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 *
 * @copyright Marius Strajeru
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 * @author    Marius Strajeru <ultimate.module.creator@gmail.com>
 *
 */
namespace App\Model\Section;

use App\Model\Section;

class SectionFactory
{
    /**
     * @param $label
     * @param $code
     * @param array $dependencies
     * @return Section
     */
    public function create($label, $code, array $dependencies = []): Section
    {
        return new Section($label, $code, $dependencies);
    }
}
