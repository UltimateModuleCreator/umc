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

namespace App\Umc\CoreBundle\Model\Attribute;

use App\Umc\CoreBundle\Model\Attribute;
use App\Umc\CoreBundle\Model\Attribute\Dynamic\Option;
use App\Umc\CoreBundle\Model\Attribute\Dynamic\Type\BaseType;
use App\Umc\CoreBundle\Model\Attribute\Dynamic\Option\Factory as OptionFactory;
use App\Umc\CoreBundle\Model\Attribute\Dynamic\Type\Factory as TypeFactory;
use App\Umc\CoreBundle\Util\StringUtil;

class Dynamic
{
    /**
     * @var string
     */
    protected $code;
    /**
     * @var string
     */
    protected $label;
    /**
     * @var string
     */
    protected $type;
    /**
     * @var bool
     */
    protected $expanded;
    /**
     * @var bool
     */
    protected $required;
    /**
     * @var bool
     */
    protected $showInList;
    /**
     * @var bool
     */
    protected $showInView;
    /**
     * @var string
     */
    protected $note;
    /**
     * @var string
     */
    protected $tooltip;
    /**
     * @var string
     */
    protected $defaultValue;
    /**
     * @var Attribute
     */
    protected $attribute;
    /**
     * @var OptionFactory
     */
    protected $optionFactory;
    /**
     * @var StringUtil
     */
    protected $stringUtil;
    /**
     * @var Option[]
     */
    protected $options;
    /**
     * @var TypeFactory
     */
    protected $typeFactory;
    /**
     * @var BaseType
     */
    protected $typeInstance;
    /**
     * @var string
     */
    protected $optionType;

    /**
     * Dynamic constructor.
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
            $data['_option'] ?? []
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
        return $this->getTypeInstance()->hasFlag('manual_options');
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
            '_option' => array_map(
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
}
