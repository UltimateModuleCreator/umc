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

class Serialized
{
    /**
     * @var string
     */
    private $code;
    /**
     * @var string
     */
    private $label;
    /**
     * @var string
     */
    private $type;
    /**
     * @var bool
     */
    private $expanded;
    /**
     * @var bool
     */
    private $required;
    /**
     * @var bool
     */
    private $showInList;
    /**
     * @var bool
     */
    private $showInView;
    /**
     * @var string
     */
    private $note;
    /**
     * @var string
     */
    private $tooltip;
    /**
     * @var string
     */
    private $defaultValue;
    /**
     * @var Attribute
     */
    private $attribute;
    /**
     * @var SerializedOptionFactory
     */
    private $optionFactory;
    /**
     * @var SerializedOption[]
     */
    private $options;

    /**
     * Serialized constructor.
     * @param Attribute $attribute
     * @param SerializedOptionFactory $optionFactory
     */
    public function __construct(SerializedOptionFactory $optionFactory, Attribute $attribute, array $data = [])
    {
        $this->attribute = $attribute;
        $this->optionFactory = $optionFactory;
        $this->code = (string)($data['code'] ?? '');
        $this->label = (string)($data['label'] ?? '');
        $this->type = (string)($data['type'] ?? '');
        $this->expanded = (bool)($data['expanded'] ?? false);
        $this->required = (bool)($data['required'] ?? false);
        $this->showInList = (bool)($data['show_in_list'] ?? false);
        $this->showInView = (bool)($data['show_in_view'] ?? false);
        $this->note = (string)($data['note'] ?? '');
        $this->tooltip = (string)($data['tooltip'] ?? '');
        $this->defaultValue = (string)($data['default_value'] ?? '');
        $this->options = array_map(
            function (array $option) {
                return $this->optionFactory->create($this, $option);
            },
            $data['_options'] ?? []
        );
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isExpanded(): bool
    {
        return $this->expanded;
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @return bool
     */
    public function isShowInList(): bool
    {
        return $this->showInList;
    }

    /**
     * @return bool
     */
    public function isShowInView(): bool
    {
        return $this->showInView;
    }

    /**
     * @return string
     */
    public function getNote(): string
    {
        return $this->note;
    }

    /**
     * @return string
     */
    public function getTooltip(): string
    {
        return $this->tooltip;
    }

    /**
     * @return string
     */
    public function getDefaultValue(): string
    {
        return $this->defaultValue;
    }

    /**
     * @return Attribute
     */
    public function getAttribute(): Attribute
    {
        return $this->attribute;
    }

    /**
     * @return SerializedOption[]
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'label' => $this->label,
            'type' => $this->type,
            'expanded' => $this->expanded,
            'required' => $this->required,
            'showInList' => $this->showInList,
            'showInView' => $this->showInView,
            'note' => $this->note,
            'tooltip' => $this->tooltip,
            'default_value' => $this->defaultValue,
            '_options' => array_map(
                function (SerializedOption $option) {
                    return $option->toArray();
                },
                $this->getOptions()
            )
        ];
    }
}
