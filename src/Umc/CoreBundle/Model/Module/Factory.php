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
use App\Service\License\ProcessorInterface;
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
     * @var ProcessorInterface[]
     */
    protected $licenseFormatter;
    /**
     * @var string;
     */
    protected $moduleClassName;

    /**
     * ModuleFactory constructor.
     * @param StringUtil $stringUtil
     * @param EntityFactory $entityFactory
     * @param array $licenseFormatter
     */
    public function __construct(
        StringUtil $stringUtil,
        EntityFactory $entityFactory,
        string $moduleClassName = Module::class
//        array $licenseFormatter
    ) {
        $this->stringUtil = $stringUtil;
        $this->entityFactory = $entityFactory;
        $this->moduleClassName = $moduleClassName;
//        $this->licenseFormatter = $licenseFormatter;
    }

    /**
     * @param array $data
     * @return Module
     */
    public function create(array $data = []): Module
    {
        //return new Module($this->stringUtil, $this->entityFactory, $this->licenseFormatter, $this->menuConfig, $data);
        $className = $this->moduleClassName;
        return new $className($this->stringUtil, $this->entityFactory, /*$this->licenseFormatter, [],*/ $data);
    }
}
