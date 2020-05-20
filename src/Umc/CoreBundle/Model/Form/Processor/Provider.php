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

namespace App\Umc\CoreBundle\Model\Form\Processor;

use App\Umc\CoreBundle\Model\OptionProvider\MissingProviderException;
use App\Umc\CoreBundle\Model\OptionProvider\Pool;

/**
 * @deprecated
 */
class Provider implements ProcessorInterface
{
    /**
     * @var Pool
     */
    private $pool;
    /**
     * @var bool
     */
    private $silent;
    /**
     * @var array
     */
    private $fields;

    /**
     * Provider constructor.
     * @param Pool $pool
     * @param array $fields
     * @param bool $silent
     */
    public function __construct(Pool $pool, array $fields, bool $silent)
    {
        $this->pool = $pool;
        $this->silent = $silent;
        $this->fields = $fields;
    }

    /**
     * @param array $config
     * @return array
     */
    public function process(array $config): array
    {
        if (is_array($config)) {
            foreach ($this->fields as $from => $to) {
                if (isset($config[$from])) {
                    try {
                        $config[$to] = $this->pool->getProvider($config[$from])->getOptions();
                    } catch (MissingProviderException $e) {
                        if ($this->silent) {
                            $config[$to] = [];
                        } else {
                            throw $e;
                        }
                    }
                }
                foreach ($config as $key => $realValue) {
                    if (is_array($realValue)) {
                        $config[$key] = $this->process($realValue);
                    }
                }
            }
        }
        return $config;
    }

}
