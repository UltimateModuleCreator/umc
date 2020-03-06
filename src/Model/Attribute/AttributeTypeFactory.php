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
use App\Model\AttributeType;

class AttributeTypeFactory
{
    /**
     * @var string
     */
    private const DEFAULT_TYPE_CLASS = AttributeType::class;
    /**
     * @var array
     */
    private $typeMap;

    /**
     * AttributeTypeFactory constructor.
     * @param array $typeMap
     */
    public function __construct(array $typeMap)
    {
        $this->typeMap = $typeMap;
    }

    /**
     * @param Attribute $attribute
     * @param array $data
     * @return AttributeType
     */
    public function create(Attribute $attribute): AttributeType
    {
        $class = $data['class'] ?? self::DEFAULT_TYPE_CLASS;
        return new $class($attribute, $this->typeMap[$attribute->getType()] ?? []);
    }
}