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

namespace App\Umc\CoreBundle\Config;

use App\Umc\CoreBundle\Util\Sorter;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ParamMerger
{
    const VALUE = 'value';
    const FLAGS = 'flags';
    const SORT_FLAG = 'sort';
    const FILTER_FLAG = 'filter';
    const EXTEND_FLAG = 'extends';
    /**
     * @param ParameterBagInterface $parameterBag
     * @param $params
     * @return array
     */
    public function mergeParams(ParameterBagInterface $parameterBag, $params)
    {
        $parameters = [];
        $sorter = new Sorter();
        foreach ($params as $key => $item) {
            $flags = $item[self::FLAGS] ?? [];
            $parameter = [];
            if (isset($flags[self::EXTEND_FLAG])) {
                try {
                    $parameter = $parameterBag->get($flags[self::EXTEND_FLAG]);
                } catch (ParameterNotFoundException $e) {
                    $parameter = [];
                }
            }
            $parameter = array_replace_recursive($parameter, $item[self::VALUE] ?? []);
            if (isset($flags[self::FILTER_FLAG])) {
                $field = $flags[self::FILTER_FLAG];
                $parameter = array_filter(
                    $parameter,
                    function ($item) use ($field) {
                        return !isset($item[$field]) || !$item[$field];
                    }
                );
            }
            if (isset($flags[self::SORT_FLAG])) {
                $field = $flags[self::SORT_FLAG];
                $parameter = $sorter->sort($parameter, $field);
            }
            $parameters[$key] = $parameter;
        }
        return $parameters;
    }
}
