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

namespace App\Umc\CoreBundle\Util;

class StringUtil
{
    /**
     * @var string[]
     */
    private $camelCache = [];
    /**
     * @var string[];
     */
    private $snakeCache = [];
    /**
     * @param string $string
     * @return string
     */
    public function camel(string $string): string
    {
        if (!array_key_exists($string, $this->camelCache)) {
            $this->camelCache[$string] = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $string))));
        }
        return $this->camelCache[$string];
    }

    /**
     * @param $string
     * @return string
     */
    public function snake(string $string): string
    {
        if (!array_key_exists($string, $this->snakeCache)) {
            $this->snakeCache[$string] = strtolower(trim(preg_replace('/([A-Z]|[0-9]+)/', "_$1", $string), '_'));
        }
        return $this->snakeCache[$string];
    }

    /**
     * @param $array
     * @return array
     */
    public function snakeArray($array): array
    {
        return array_map(
            function (string $string) {
                return $this->snake($string);
            },
            $array
        );
    }

    /**
     * @param string $string
     * @return string
     */
    public function ucfirst(?string $string): string
    {
        return ucfirst($string);
    }

    /**
     * @param string $string
     * @return string
     */
    public function hyphen(string $string): string
    {
        return str_replace('_', '-', $this->snake($string));
    }

    /**
     * @param $string
     * @param int $length
     * @return string
     */
    public function repeat($string, $length = 1): string
    {
        return str_repeat($string, max($length, 0));
    }

    /**
     * @param $parts
     * @param string $glue
     * @return string
     */
    public function glueClassParts($parts, $glue = '\\'): string
    {
        return implode(
            $glue,
            array_map(
                function (string $text) {
                    return $this->ucfirst(
                        $this->camel($text)
                    );
                },
                $parts
            )
        );
    }
}
