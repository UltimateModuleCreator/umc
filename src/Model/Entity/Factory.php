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

namespace App\Model\Entity;

use App\Model\Attribute\Factory as AttributeFactory;
use App\Model\Entity;
use App\Model\Module;
use App\Util\StringUtil;

class Factory
{
    /**
     * @var AttributeFactory
     */
    private $attributeFactory;
    /**
     * @var StringUtil
     */
    private $stringUtil;

    /**
     * EntityFactory constructor.
     * @param StringUtil $stringUtil
     */
    public function __construct(AttributeFactory $attributeFactory, StringUtil $stringUtil)
    {
        $this->attributeFactory = $attributeFactory;
        $this->stringUtil = $stringUtil;
    }

    /**
     * @param array $data
     * @return Entity
     */
    public function create(Module $module, array $data = []): Entity
    {
        return new Entity($this->stringUtil, $this->attributeFactory, $module, $data);
    }
}
