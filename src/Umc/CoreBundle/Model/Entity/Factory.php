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

namespace App\Umc\CoreBundle\Model\Entity;

use App\Umc\CoreBundle\Model\Attribute\Factory as AttributeFactory;
use App\Umc\CoreBundle\Util\StringUtil;
use App\Umc\CoreBundle\Model\Entity;
use App\Umc\CoreBundle\Model\Module;

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
     * @var string
     */
    private $className;

    /**
     * Factory constructor.
     * @param AttributeFactory $attributeFactory
     * @param StringUtil $stringUtil
     * @param string $className
     */
    public function __construct(AttributeFactory $attributeFactory, StringUtil $stringUtil, $className = Entity::class)
    {
        $this->attributeFactory = $attributeFactory;
        $this->stringUtil = $stringUtil;
        $this->className = $className;
    }

    /**
     * @param Module $module
     * @param array $data
     * @return Entity
     */
    public function create(Module $module, array $data = []): Entity
    {
        $className = $this->className;
        return new $className($this->stringUtil, $this->attributeFactory, $module, $data);
    }
}
