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
 */
declare(strict_types=1);

namespace App\Util;

use Symfony\Component\Yaml\Yaml;

class YamlLoader
{
    /**
     * @param $file
     * @return array
     * @throws \Exception
     */
    public function load($file) : array
    {
        try {
            $values = Yaml::parseFile($file, 1);
        } catch (\Exception $e) {
            $values = null;
        }
        if (null === $values) {
            throw new \Exception(sprintf('Could not load file %s', $file));
        }
        return $values;
    }

    /**
     * @param array $array
     * @return string
     */
    public function arrayToYaml(array $array) : string
    {
        return Yaml::dump($array);
    }
}
