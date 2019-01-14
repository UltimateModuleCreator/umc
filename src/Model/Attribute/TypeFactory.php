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

class TypeFactory
{
    /**
     * @var array
     */
    private $map = [];
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * TypeFactory constructor.
     * @param array $map
     * @param \Twig_Environment $twig
     */
    public function __construct(array $map, \Twig_Environment $twig)
    {
        $this->map = $map;
        $this->twig = $twig;
    }

    /**
     * @param Attribute $attribute
     * @return TypeInterface
     * @throws \Exception
     */
    public function create(Attribute $attribute) : TypeInterface
    {
        $type = $attribute->getType();
        if (!isset($this->map[$type]['class'])) {
            throw new \Exception("Attribute type {$type} is not supported");
        }
        $class = $this->map[$type]['class'];

        if (!is_subclass_of($class, TypeInterface::class)) {
            throw new \Exception(
                "Class {$class} for attribute type {$type} is not and instance of" . TypeInterface::class
            );
        }
        return new $class($this->twig, $attribute, $this->map[$type]);
    }
}
