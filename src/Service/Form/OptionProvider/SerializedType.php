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

namespace App\Service\Form\OptionProvider;

use App\Service\Form\OptionProviderInterface;

class SerializedType implements OptionProviderInterface
{
    const FILTER_FIELD = 'can_be_serialized';
    /**
     * @var AttributeType
     */
    private $attributeType;
    /**
     * @var array
     */
    private $options;

    /**
     * SerializedType constructor.
     * @param AttributeType $attributeType
     */
    public function __construct(AttributeType $attributeType)
    {
        $this->attributeType = $attributeType;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        if ($this->options === null) {
            $this->options = [];
            $allOptions = $this->attributeType->getOptions();
            foreach ($allOptions as $group => $fields) {
                foreach ($fields as $key => $field) {
                    if ($field[self::FILTER_FIELD] ?? false) {
                        $this->options[$group] = $this->options[$group] ?? [];
                        $this->options[$group][$key] = $field;
                    }
                }
            }
        }
        return $this->options;
    }
}
