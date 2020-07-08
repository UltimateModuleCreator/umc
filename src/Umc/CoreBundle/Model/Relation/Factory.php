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

namespace App\Umc\CoreBundle\Model\Relation;

use App\Umc\CoreBundle\Model\Module;
use App\Umc\CoreBundle\Model\Relation;
use App\Umc\CoreBundle\Util\StringUtil;

class Factory
{
    /**
     * @var StringUtil
     */
    private $stringUtil;

    /**
     * Factory constructor.
     * @param StringUtil $stringUtil
     */
    public function __construct(StringUtil $stringUtil)
    {
        $this->stringUtil = $stringUtil;
    }

    /**
     * @param Module $module
     * @param array $data
     * @return Relation
     */
    public function create(Module $module, array $data): Relation
    {
        return new Relation($module, $this->stringUtil, $data);
    }
}
