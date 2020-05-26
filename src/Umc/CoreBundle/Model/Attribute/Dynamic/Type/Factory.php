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

namespace App\Umc\CoreBundle\Model\Attribute\Dynamic\Type;

use App\Umc\CoreBundle\Model\Attribute\Dynamic;

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
     * Factory constructor.
     * @param \Twig\Environment $twig
     * @param array $typeMap
     */
    public function __construct(\Twig\Environment $twig, array $typeMap)
    {
        $this->twig = $twig;
        $this->typeMap = array_filter(
            $typeMap,
            function ($item) {
                return isset($item['can_be_dynamic']) && $item['can_be_dynamic'];
            }
        );
    }

    /**
     * @param Dynamic $dynamic
     * @return BaseType
     */
    public function create(Dynamic $dynamic): BaseType
    {
        $type = $dynamic->getType();
        if (!isset($this->typeMap[$type])) {
            throw new \InvalidArgumentException("There is no config for dynamic type {$type}");
        }
        $config = $this->typeMap[$type];
        $class = $config['dynamic_class'] ?? self::DEFAULT_TYPE_CLASS;
        return new $class($this->twig, $dynamic, $config);
    }
}
