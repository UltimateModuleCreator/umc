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

namespace App\Model\Attribute\Type;

use App\Model\Attribute;

class Factory
{
    /**
     * @var string
     */
    private const DEFAULT_TYPE_CLASS = BaseType::class;
    /**
     * @var array
     */
    private $typeMap;
    /**
     * @var \Twig\Environment;
     */
    private $twig;

    /**
     * AttributeTypeFactory constructor.
     * @param array $typeMap
     */
    public function __construct(\Twig\Environment $twig, array $typeMap)
    {
        $this->twig = $twig;
        $this->typeMap = $typeMap;
    }

    /**
     * @param Attribute $attribute
     * @param array $data
     * @return BaseType
     */
    public function create(Attribute $attribute): BaseType
    {
        $type = $attribute->getType();
        if (!isset($this->typeMap[$type])) {
            throw new \InvalidArgumentException("There is no config for attribute type {$type}");
        }
        $config = $this->typeMap[$type];
        $class = $config['class'] ?? self::DEFAULT_TYPE_CLASS;
        return new $class($this->twig, $attribute, $config);
    }
}
