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
    public const VALUE = 'value';
    public const FLAGS = 'flags';
    public const SORT_FLAG = 'sort';
    public const FILTER_FLAG = 'filter';
    public const EXTEND_FLAG = 'extends';
    public const NOT = '!';
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
                if (isset($parameters[$flags[self::EXTEND_FLAG]])) {
                    $parameter = $parameters[$flags[self::EXTEND_FLAG]];
                } else {
                    try {
                        $parameter = $parameterBag->get($flags[self::EXTEND_FLAG]);
                    } catch (ParameterNotFoundException $e) {
                        $parameter = [];
                    }
                }
            }
            $parameter = array_replace_recursive($parameter, $item[self::VALUE] ?? []);
            if (isset($flags[self::FILTER_FLAG])) {
                $field = $flags[self::FILTER_FLAG];
                if (substr($field, 0, strlen(self::NOT)) === self::NOT) {
                    $not = true;
                    $field = substr($field, strlen(self::NOT));
                } else {
                    $not = false;
                }
                $parameter = array_filter(
                    $parameter,
                    function ($item) use ($field, $not) {
                        return ($not)
                            ? isset($item[$field]) && $item[$field]
                            : !isset($item[$field]) || !$item[$field];
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
