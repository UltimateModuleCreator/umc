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

use App\Util\StringUtil;

class SerializedFactory
{
    /**
     * @var SerializedOptionFactory
     */
    private $optionFactory;
    /**
     * @var StringUtil
     */
    private $stringUtil;

    /**
     * SerializedFactory constructor.
     * @param SerializedOptionFactory $optionFactory
     */
    public function __construct(SerializedOptionFactory $optionFactory, StringUtil $stringUtil)
    {
        $this->optionFactory = $optionFactory;
        $this->stringUtil = $stringUtil;
    }

    /**
     * @param Attribute $attribute
     * @param array $data
     * @return Serialized
     */
    public function create(Attribute $attribute, array $data = []): Serialized
    {
        return new Serialized($this->optionFactory, $this->stringUtil, $attribute, $data);
    }
}
