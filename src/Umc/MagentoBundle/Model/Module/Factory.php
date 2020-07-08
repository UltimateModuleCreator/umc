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

namespace App\Umc\MagentoBundle\Model\Module;

use App\Umc\CoreBundle\Model\Entity\Factory as EntityFactory;
use App\Umc\CoreBundle\Model\Relation\Factory as RelationFactory;
use App\Umc\CoreBundle\Model\Module;
use App\Umc\CoreBundle\Util\StringUtil;

class Factory extends \App\Umc\CoreBundle\Model\Module\Factory
{
    /**
     * @var array
     */
    protected $menuConfig;

    /**
     * Factory constructor.
     * @param StringUtil $stringUtil
     * @param EntityFactory $entityFactory
     * @param RelationFactory $relationFactory
     * @param array $menuConfig
     * @param string $moduleClassName
     */
    public function __construct(
        StringUtil $stringUtil,
        EntityFactory $entityFactory,
        RelationFactory $relationFactory,
        array $menuConfig,
        string $moduleClassName = Module::class
    ) {
        $this->menuConfig = $menuConfig;
        parent::__construct($stringUtil, $entityFactory, $relationFactory, $moduleClassName);
    }

    /**
     * @param array $data
     * @return Module | \App\Umc\MagentoBundle\Model\Module
     */
    public function create(array $data = []): Module
    {
        $className = $this->moduleClassName;
        return new $className(
            $this->stringUtil,
            $this->entityFactory,
            $this->relationFactory,
            $this->menuConfig,
            $data
        );
    }
}
