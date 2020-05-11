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

namespace App\Model\Attribute\Serialized;

use App\Model\Attribute;
use App\Model\Attribute\Serialized;
use App\Model\Attribute\Serialized\Option\Factory as OptionFactory;
use App\Model\Attribute\Serialized\Type\Factory as TypeFactory;
use App\Util\StringUtil;

class Factory
{
    /**
     * @var OptionFactory
     */
    private $optionFactory;
    /**
     * @var StringUtil
     */
    private $stringUtil;
    /**
     * @var TypeFactory
     */
    private $typeFactory;

    /**
     * Factory constructor.
     * @param OptionFactory $optionFactory
     * @param StringUtil $stringUtil
     * @param TypeFactory $typeFactory
     */
    public function __construct(OptionFactory $optionFactory, StringUtil $stringUtil, TypeFactory $typeFactory)
    {
        $this->optionFactory = $optionFactory;
        $this->stringUtil = $stringUtil;
        $this->typeFactory = $typeFactory;
    }

    /**
     * @param Attribute $attribute
     * @param array $data
     * @return Serialized
     */
    public function create(Attribute $attribute, array $data = []): Serialized
    {
        return new Serialized($this->optionFactory, $this->typeFactory, $this->stringUtil, $attribute, $data);
    }
}
