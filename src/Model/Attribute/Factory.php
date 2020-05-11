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
use App\Model\Attribute\Type\Factory as TypeFactory;
use App\Model\Attribute\Option\Factory as OptionFactory;
use App\Model\Attribute\Serialized\Factory as SerializedFactory;
use App\Model\Entity;
use App\Util\StringUtil;

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
     * @var SerializedFactory
     */
    private $serializedFactory;
    /**
     * @var StringUtil
     */
    private $stringUtil;

    /**
     * AttributeFactory constructor.
     * @param TypeFactory $typeFactory
     * @param OptionFactory $optionFactory
     * @param SerializedFactory $serilizedFactory
     * @param StringUtil $stringUtil
     */
    public function __construct(
        TypeFactory $typeFactory,
        OptionFactory $optionFactory,
        SerializedFactory $serilizedFactory,
        StringUtil $stringUtil
    ) {
        $this->typeFactory = $typeFactory;
        $this->optionFactory = $optionFactory;
        $this->serializedFactory = $serilizedFactory;
        $this->stringUtil = $stringUtil;
    }

    /**
     * @param Entity $entity
     * @param array $data
     * @return Attribute
     */
    public function create(Entity $entity, array $data = []): Attribute
    {
        return new Attribute(
            $this->typeFactory,
            $this->optionFactory,
            $this->serializedFactory,
            $this->stringUtil,
            $entity,
            $data
        );
    }
}
