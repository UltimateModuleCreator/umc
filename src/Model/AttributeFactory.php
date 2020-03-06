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

use App\Model\Attribute\AttributeTypeFactory;
use App\Model\Attribute\TypeFactory;

class AttributeFactory
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
     * AttributeFactory constructor.
     * @param AttributeTypeFactory $typeFactory
     * @param OptionFactory $optionFactory
     * @param SerializedFactory $serilizedFactory
     */
    public function __construct(
        AttributeTypeFactory $typeFactory,
        OptionFactory $optionFactory,
        SerializedFactory $serilizedFactory
    ) {
        $this->typeFactory = $typeFactory;
        $this->optionFactory = $optionFactory;
        $this->serializedFactory = $serilizedFactory;
    }

    /**
     * @param array $data
     * @return Attribute | AbstractModel
     */
    public function create(Entity $entity, array $data = []) : Attribute
    {
        return new Attribute($this->typeFactory, $this->optionFactory, $this->serializedFactory, $entity, $data);
    }
}
