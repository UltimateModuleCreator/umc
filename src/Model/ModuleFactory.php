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

namespace App\Model;

use App\Service\License\ProcessorInterface;
use App\Util\StringUtil;

class ModuleFactory implements FactoryInterface
{
    /**
     * @var EntityFactory
     */
    private $entityFactory;
    /**
     * @var StringUtil
     */
    private $stringUtil;
    /**
     * @var ProcessorInterface[]
     */
    private $licenseFormatter;
    /**
     * @var array
     */
    private $menuConfig;

    /**
     * ModuleFactory constructor.
     * @param StringUtil $stringUtil
     * @param EntityFactory $entityFactory
     * @param array $licenseFormatter
     * @param array $menuConfig
     */
    public function __construct(
        StringUtil $stringUtil,
        EntityFactory $entityFactory,
        array $licenseFormatter,
        array $menuConfig
    ) {
        $this->stringUtil = $stringUtil;
        $this->entityFactory = $entityFactory;
        $this->licenseFormatter = $licenseFormatter;
        $this->menuConfig = $menuConfig;
    }

    /**
     * @param array $data
     * @return Module | AbstractModel
     */
    public function create(array $data = []): Module
    {
        return new Module($this->stringUtil, $this->entityFactory, $this->licenseFormatter, $this->menuConfig, $data);
    }
}
