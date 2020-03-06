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

namespace App\Model;

class SerializedOption
{
    /**
     * @var string
     */
    private $value;
    /**
     * @var string
     */
    private $label;
    /**
     * @var bool
     */
    private $defaultRadio;
    /**
     * @var bool
     */
    private $defaultCheckbox;
    /**
     * @var Serialized
     */
    private $field;

    /**
     * Option constructor.
     * @param Serialized $field
     */
    public function __construct(Serialized $field, array $data = [])
    {
        $this->field = $field;
        $this->value = (string)($data['value'] ?? '');
        $this->label = (string)($data['label'] ?? '');
        $this->defaultCheckbox = (bool)($data['default_checkbox'] ?? false);
        $this->defaultRadio = (bool)($data['default_radio'] ?? false);
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return bool
     */
    public function isDefaultRadio(): bool
    {
        return $this->defaultRadio;
    }

    /**
     * @return bool
     */
    public function isDefaultCheckbox(): bool
    {
        return $this->defaultCheckbox;
    }

    /**
     * @return Attribute
     */
    public function getField(): Serialized
    {
        return $this->field;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'value' => $this->value,
            'label' => $this->label,
            'default_checkbox' => $this->defaultCheckbox,
            'default_radio' => $this->defaultRadio
        ];
    }
}
