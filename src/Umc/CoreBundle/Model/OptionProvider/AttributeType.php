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

namespace App\Umc\CoreBundle\Model\OptionProvider;

use App\Umc\CoreBundle\Model\Config\Loader;
use App\Umc\CoreBundle\Model\Config\Modifier\Expand\ExpandInterface;

class AttributeType implements ExpandInterface
{
    public const ATTRIBUTE_CONFIG = 'attribute_config';
    public const DEFAULT_GROUP = 'Misc';
    public const GROUP_BY = 'group';
    /**
     * @var Loader
     */
    private $configLoader;
    /**
     * @var array
     */
    private $options;

    /**
     * AttributeType constructor.
     * @param Loader $configLoader
     */
    public function __construct(Loader $configLoader)
    {
        $this->configLoader = $configLoader;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getValues(): array
    {
        if ($this->options === null) {
            $options = $this->configLoader->getConfig();
            foreach ($options as $key => $type) {
                $group = $type[self::GROUP_BY] ?? self::DEFAULT_GROUP;
                $this->options[$group] = $this->options[$group] ?? [];
                $type['value'] = $key;
                $this->options[$group][$key] = $type;
            }
            $this->options = $options;
        }
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return self::ATTRIBUTE_CONFIG;
    }
}
