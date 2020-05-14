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

namespace App\Model\Attribute;

use App\Model\Attribute;
use App\Model\Attribute\Serialized\Option;
use App\Model\Attribute\Serialized\Type\BaseType;
use App\Model\Attribute\Serialized\Option\Factory as OptionFactory;
use App\Model\Attribute\Serialized\Type\Factory as TypeFactory;
use App\Util\StringUtil;

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
     * @var OptionFactory
     */
    private $optionFactory;
    /**
     * @var StringUtil
     */
    private $stringUtil;
    /**
     * @var Option[]
     */
    private $options;
    /**
     * @var TypeFactory
     */
    private $typeFactory;
    /**
     * @var BaseType
     */
    private $typeInstance;
    /**
     * @var string
     */
    private $optionType;

    /**
     * Serialized constructor.
     * @param OptionFactory $optionFactory
     * @param TypeFactory $typeFactory
     * @param StringUtil $stringUtil
     * @param Attribute $attribute
     * @param array $data
     */
    public function __construct(
        OptionFactory $optionFactory,
        TypeFactory $typeFactory,
        StringUtil $stringUtil,
        Attribute $attribute,
        array $data = []
    ) {
        $this->attribute = $attribute;
        $this->typeFactory = $typeFactory;
        $this->optionFactory = $optionFactory;
        $this->stringUtil = $stringUtil;
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
     * @return bool
     */
    public function isManualOptions(): bool
    {
        return $this->getTypeInstance()->isCanHaveOptions();
    }

    /**
     * @return Option[]
     */
    public function getOptions(): array
    {
        return $this->isManualOptions() ? $this->options : [];
    }

    /**
     * @return string
     */
    public function getOptionType(): string
    {
        if ($this->optionType === null) {
            $this->optionType = 'number';
            foreach ($this->getOptions() as $option) {
                if (!is_numeric($option->getValue())) {
                    $this->optionType = 'string';
                    break;
                }
            }
        }
        return $this->optionType;
    }

    /**
     * @return string
     */
    public function getOptionSourceVirtualType(): string
    {
        $parts = [
            $this->getAttribute()->getEntity()->getModule()->getModuleName(),
            $this->getAttribute()->getEntity()->getNameSingular(),
            'Source',
            $this->getAttribute()->getCode(),
            $this->getCode()
        ];
        return $this->stringUtil->glueClassParts($parts, '');
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
            'show_in_list' => $this->showInList,
            'show_in_view' => $this->showInView,
            'note' => $this->note,
            'tooltip' => $this->tooltip,
            'default_value' => $this->defaultValue,
            '_options' => array_map(
                function (Option $option) {
                    return $option->toArray();
                },
                $this->options
            )
        ];
    }

    /**
     * @return BaseType
     */
    public function getTypeInstance(): BaseType
    {
        if ($this->typeInstance === null) {
            $this->typeInstance = $this->typeFactory->create($this);
        }
        return $this->typeInstance;
    }

    /**
     * @param $type
     * @return array
     */
    public function getProcessorTypes($type): array
    {
        return $this->getTypeInstance()->getProcessorTypes($type);
    }
}
