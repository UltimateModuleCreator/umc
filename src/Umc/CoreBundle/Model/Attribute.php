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

namespace App\Umc\CoreBundle\Model;

use App\Umc\CoreBundle\Model\Attribute\Dynamic;
use App\Umc\CoreBundle\Model\Attribute\Dynamic\Factory as DynamicFactory;
use App\Umc\CoreBundle\Model\Attribute\Type\BaseType;
use App\Umc\CoreBundle\Model\Attribute\Type\Factory as TypeFactory;
use App\Umc\CoreBundle\Model\Attribute\Option;
use App\Umc\CoreBundle\Model\Attribute\Option\Factory as OptionFactory;
use App\Umc\CoreBundle\Util\StringUtil;

class Attribute
{
    /**
     * @var BaseType
     */
    protected $typeInstance;
    /**
     * @var TypeFactory
     */
    protected $typeFactory;
    /**
     * @var Entity
     */
    protected $entity;
    /**
     * @var OptionFactory
     */
    protected $optionFactory;
    /**
     * @var DynamicFactory
     */
    protected $dynamicFactory;
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
    protected $isName;
    /**
     * @var bool
     */
    protected $adminGrid;
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
    protected $defaultValue;
    /**
     * @var bool
     */
    protected $adminGridHidden;
    /**
     * @var bool
     */
    protected $adminGridFilter;
    /**
     * @var string
     */
    protected $note;
    /**
     * @var string
     */
    protected $tooltip;
    /**
     * @var Option[]
     */
    protected $options;
    /**
     * @var Attribute\Dynamic[]
     */
    protected $dynamic;
    /**
     * @var StringUtil
     */
    protected $stringUtil;
    /**
     * @var Attribute\Dynamic[]
     */
    protected $dynamicWithOptions;
    /**
     * @var bool
     */
    protected $areOptionsNumeric;
    /**
     * @var string[]
     */
    protected $flags;
    /**
     * @var string[][]
     */
    protected $flagSuffixes = [];

    /**
     * Attribute constructor.
     * @param TypeFactory $typeFactory
     * @param OptionFactory $optionFactory
     * @param DynamicFactory $dynamicFactory
     * @param StringUtil $stringUtil
     * @param Entity $entity
     * @param array $data
     */
    public function __construct(
        TypeFactory $typeFactory,
        OptionFactory $optionFactory,
        DynamicFactory $dynamicFactory,
        StringUtil $stringUtil,
        Entity $entity,
        array $data = []
    ) {
        $this->typeFactory = $typeFactory;
        $this->optionFactory = $optionFactory;
        $this->dynamicFactory = $dynamicFactory;
        $this->stringUtil = $stringUtil;
        $this->entity = $entity;
        $this->code = (string)($data['code'] ?? '');
        $this->label = (string)($data['label'] ?? '');
        $this->type = (string)($data['type'] ?? 'text');
        $this->isName = (bool)($data['is_name'] ?? '');
        $this->required = (bool)($data['required'] ?? false);
        $this->adminGrid = (bool)($data['admin_grid'] ?? false);
        $this->showInList = (bool)($data['show_in_list'] ?? false);
        $this->showInView = (bool)($data['show_in_view'] ?? false);
        $this->defaultValue = (string)($data['default_value'] ?? '');
        $this->adminGridHidden = (bool)($data['admin_grid_hidden'] ?? false);
        $this->adminGridFilter = (bool)($data['admin_grid_filter'] ?? false);
        $this->note = (string)($data['note'] ?? '');
        $this->tooltip = (string)($data['tooltip'] ?? '');
        $this->options = array_map(
            function ($option) {
                return $this->optionFactory->create($this, $option);
            },
            $data['_option'] ?? []
        );
        $this->dynamic = array_map(
            function ($dynamic) {
                return $this->dynamicFactory->create($this, $dynamic);
            },
            $data['_dynamic'] ?? []
        );
    }

    /**
     * @return Entity
     */
    public function getEntity(): Entity
    {
        return $this->entity;
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
    public function isName(): bool
    {
        return $this->isName && $this->getTypeInstance()->hasFlag('can_be_name');
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required && $this->getTypeInstance()->hasFlag('can_be_required');
    }

    /**
     * @return bool
     */
    public function isShowInList(): bool
    {
        return $this->getEntity()->isFrontend() && $this->showInList;
    }

    /**
     * @return bool
     */
    public function isShowInView(): bool
    {
        return $this->getEntity()->isFrontend() && $this->showInView;
    }

    /**
     * @return string
     */
    public function getRawDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @return string
     */
    public function getDefaultValue(): string
    {
        return $this->getTypeInstance()->getDefaultValue();
    }

    /**
     * @return bool
     */
    public function isAdminGrid(): bool
    {
        return $this->adminGrid && $this->getTypeInstance()->hasFlag('can_show_in_grid');
    }

    /**
     * @return string
     */
    public function getIndexDeleteType()
    {
        return $this->isRequired() ? 'CASCADE' : 'SET NULL';
    }

    /**
     * @return bool
     */
    public function isAdminGridHidden(): bool
    {
        return $this->isAdminGrid() && $this->adminGridHidden;
    }

    /**
     * @return bool
     */
    public function isAdminGridFilter(): bool
    {
        $canFilterInGrid = $this->getTypeInstance()->hasFlag('can_filter_in_grid');
        return $this->isAdminGrid() && $this->adminGridFilter && $canFilterInGrid;
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
     * @return Option[]
     */
    public function getOptions(): array
    {
        return $this->isManualOptions() ? $this->options : [];
    }

    /**
     * @return bool
     */
    public function isDynamic(): bool
    {
        return $this->type === 'dynamic';
    }

    /**
     * @return Attribute\Dynamic[]
     */
    public function getDynamic(): array
    {
        return $this->isDynamic() ? $this->dynamic : [];
    }

    /**
     * @return Attribute\Dynamic[]
     */
    public function getDynamicWithOptions(): array
    {
        if ($this->dynamicWithOptions === null) {
            $this->dynamicWithOptions = array_values(
                array_filter(
                    $this->getDynamic(),
                    function (Dynamic $dynamic) {
                        return $dynamic->isManualOptions();
                    }
                )
            );
        }
        return $this->dynamicWithOptions;
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
     * @return bool
     */
    public function isManualOptions(): bool
    {
        return (bool)$this->getTypeInstance()->hasFlag('manual_options');
    }

    /**
     * @return bool
     */
    public function areOptionsNumerical(): bool
    {
        if ($this->areOptionsNumeric === null) {
            $this->areOptionsNumeric = true;
            foreach ($this->getOptions() as $option) {
                if (!is_numeric($option->getValue())) {
                    $this->areOptionsNumeric = false;
                    break;
                }
            }
        }
        return $this->areOptionsNumeric;
    }

    /**
     * @return array
     */
    public function getFlags()
    {
        if ($this->flags === null) {
            $this->flags = [];
            $this->isShowInList() && $this->flags[] = 'show_in_list';
            $this->isShowInView() && $this->flags[] = 'show_in_view';
            $this->flags = array_merge($this->flags, $this->getTypeInstance()->getFlags());
        }
        return $this->flags;
    }

    /**
     * @param $flag
     * @return bool
     */
    public function hasFlag($flag): bool
    {
        return in_array($flag, $this->getFlags());
    }

    /**
     * @param $prefix
     * @return string[]
     */
    public function getFlagSuffixes($prefix): array
    {
        if (!isset($this->flagSuffixes[$prefix])) {
            $this->flagSuffixes[$prefix] = [];
            foreach ($this->getFlags() as $flag) {
                if (substr($flag, 0, strlen($prefix)) === $prefix) {
                    $this->flagSuffixes[$prefix][] = substr($flag, strlen($prefix));
                }
            }
            $this->flagSuffixes[$prefix] = array_unique($this->flagSuffixes[$prefix]);
        }
        return $this->flagSuffixes[$prefix];
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
            'is_name' => $this->isName,
            'required' => $this->required,
            'admin_grid' => $this->adminGrid,
            'show_in_list' => $this->showInList,
            'show_in_view' => $this->showInView,
            'default_value' => $this->defaultValue,
            'admin_grid_hidden' => $this->adminGridHidden,
            'admin_grid_filter' => $this->adminGridFilter,
            'note' => $this->note,
            'tooltip' => $this->tooltip,
            '_option' => array_map(
                function (Option $option) {
                    return $option->toArray();
                },
                $this->options
            ),
            '_dynamic' => array_map(
                function (Dynamic $dynamic) {
                    return $dynamic->toArray();
                },
                $this->dynamic
            ),
        ];
    }
}
