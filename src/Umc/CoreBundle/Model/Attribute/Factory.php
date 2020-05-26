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

namespace App\Umc\CoreBundle\Model\Attribute;

use App\Umc\CoreBundle\Model\Attribute;
use App\Umc\CoreBundle\Model\Attribute\Type\Factory as TypeFactory;
use App\Umc\CoreBundle\Model\Attribute\Option\Factory as OptionFactory;
use App\Umc\CoreBundle\Model\Attribute\Dynamic\Factory as DynamicFactory;
use App\Umc\CoreBundle\Model\Entity;
use App\Umc\CoreBundle\Util\StringUtil;

class Factory
{
    /**
     * @var TypeFactory
     */
    private $typeFactory;
    /**
     * @var OptionFactory
     */
    private $optionFactory;
    /**
     * @var DynamicFactory
     */
    private $dynamicFactory;
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
     * @param TypeFactory $typeFactory
     * @param OptionFactory $optionFactory
     * @param DynamicFactory $dynamicFactory
     * @param StringUtil $stringUtil
     * @param string $className
     */
    public function __construct(
        TypeFactory $typeFactory,
        OptionFactory $optionFactory,
        DynamicFactory $dynamicFactory,
        StringUtil $stringUtil,
        string $className = Attribute::class
    ) {
        $this->typeFactory = $typeFactory;
        $this->optionFactory = $optionFactory;
        $this->dynamicFactory = $dynamicFactory;
        $this->stringUtil = $stringUtil;
        $this->className = $className;
    }

    /**
     * @param Entity $entity
     * @param array $data
     * @return Attribute
     */
    public function create(Entity $entity, array $data = []): Attribute
    {
        $className = $this->className;
        return new $className(
            $this->typeFactory,
            $this->optionFactory,
            $this->dynamicFactory,
            $this->stringUtil,
            $entity,
            $data
        );
    }
}
