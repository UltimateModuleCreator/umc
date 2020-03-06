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

namespace App\Service\Form;

use App\Util\OptionGroup;
use App\Util\YamlLoader;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class Loader
{
    const DEFAULT_GROUP = 'Misc';
    /**
     * @var YamlLoader
     */
    private $yamlLoader;
    /**
     * @var array
     */
    private $fileMap;
    /**
     * @var OptionProviderPool
     */
    private $optionProviderPool;

    /**
     * Loader constructor.
     * @param YamlLoader $yamlLoader
     * @param array $fileMap
     * @param OptionProviderPool $optionProviderPool
     */
    public function __construct(YamlLoader $yamlLoader, array $fileMap, OptionProviderPool $optionProviderPool)
    {
        $this->yamlLoader = $yamlLoader;
        $this->fileMap = $fileMap;
        $this->optionProviderPool = $optionProviderPool;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getForms()
    {
        $forms = [];
        foreach ($this->fileMap as $key => $file) {
            $forms[$key] = $this->parseValue($this->yamlLoader->load($file));
        }
        return $forms;
    }

    /**
     * @param $value
     * @return array|mixed
     */
    private function parseValue($value)
    {
        if (is_array($value)) {
            if (isset($value['optionProvider'])) {
                $value['options'] = $this->optionProviderPool->getProvider($value['optionProvider'])->getOptions();
            }
            foreach ($value as $key => $realValue) {
                $value[$key] = $this->parseValue($realValue);
            }
        }
        return $value;
    }
}
