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

namespace App\Umc\CoreBundle\Model\Attribute\Dynamic;

use App\Umc\CoreBundle\Model\Attribute;
use App\Umc\CoreBundle\Model\Attribute\Dynamic;
use App\Umc\CoreBundle\Model\Attribute\Dynamic\Option\Factory as OptionFactory;
use App\Umc\CoreBundle\Model\Attribute\Dynamic\Type\Factory as TypeFactory;
use App\Umc\CoreBundle\Util\StringUtil;

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
     * @var string
     */
    private $className = Dynamic::class;

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
     * @return Dynamic
     */
    public function create(Attribute $attribute, array $data = []): Dynamic
    {
        $className = $$this->Factory;
        return new $className($this->optionFactory, $this->typeFactory, $this->stringUtil, $attribute, $data);
    }
}
