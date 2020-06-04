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

namespace App\Umc\CoreBundle\Model\Module;

use App\Umc\CoreBundle\Model\Entity\Factory as EntityFactory;
use App\Umc\CoreBundle\Model\Module;
use App\Umc\CoreBundle\Util\StringUtil;

class Factory
{
    /**
     * @var EntityFactory
     */
    protected $entityFactory;
    /**
     * @var StringUtil
     */
    protected $stringUtil;
    /**
     * @var string;
     */
    protected $moduleClassName;

    /**
     * Factory constructor.
     * @param StringUtil $stringUtil
     * @param EntityFactory $entityFactory
     * @param string $moduleClassName
     */
    public function __construct(
        StringUtil $stringUtil,
        EntityFactory $entityFactory,
        string $moduleClassName = Module::class
    ) {
        $this->stringUtil = $stringUtil;
        $this->entityFactory = $entityFactory;
        $this->moduleClassName = $moduleClassName;
    }

    /**
     * @param array $data
     * @return Module
     */
    public function create(array $data = []): Module
    {
        $className = $this->moduleClassName;
        return new $className($this->stringUtil, $this->entityFactory, [], $data);
    }
}
