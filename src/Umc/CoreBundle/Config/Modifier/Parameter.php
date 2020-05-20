<?php

/**
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

namespace App\Umc\CoreBundle\Config\Modifier;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Parameter implements ModifierInterface
{
    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    /**
     * Parameter constructor.
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    /**
     * @param array $config
     * @return array
     */
    public function modify(array $config): array
    {
        return $this->parameterBag->resolveValue($config);
    }

}
