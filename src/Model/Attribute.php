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

use App\Model\Attribute\AttributeTypeFactory;
use App\Model\Attribute\TypeInterface;
use App\Util\StringUtil;

class Attribute
{
    /**
     * @var TypeInterface
     */
    private $typeInstance;
    /**
     * @var AttributeTypeFactory
     */
    private $typeFactory;
    /**
     * @var Entity
     */
    private $entity;
    /**
     * @var OptionFactory
     */
    private $optionFactory;
    /**
     * @var SerializedFactory
     */
    private $serializedFactory;
    /**
     * @var array
     */
    private $processedOptions;
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
    private $isName;
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
     * @var bool
     */
    private $adminGrid;
    /**
     * @var bool
     */
    private $adminGridHidden;
    /**
     * @var bool
     */
    private $adminGridFilter;
    /**
     * @var string
     */
    private $note;
    /**
     * @var string
     */
    private $tooltip;
    /**
     * @var bool
     */
    private $fullText;
    /**
     * @var bool
     */
    private $expanded;
    /**
     * @var string
     */
    private $defaultValue;
    /**
     * @var Option[]
     */
    private $options;
    /**
     * @var Serialized[]
     */
    private $serialized;
    /**
     * @var StringUtil
     */
    private $stringUtil;

    /**
     * Attribute constructor.
     * @param AttributeTypeFactory $typeFactory
     * @param OptionFactory $optionFactory
     * @param SerializedFactory $serializedFactory
     * @param StringUtil $stringUtil
     * @param Entity $entity
     * @param array $data
     */
    public function __construct(
        AttributeTypeFactory $typeFactory,
        OptionFactory $optionFactory,
        SerializedFactory $serializedFactory,
        StringUtil $stringUtil,
        Entity $entity,
        array $data = []
    ) {
        $this->typeFactory = $typeFactory;
        $this->optionFactory = $optionFactory;
        $this->serializedFactory = $serializedFactory;
        $this->stringUtil = $stringUtil;
        $this->entity = $entity;
        $this->code = (string)($data['code'] ?? '');
        $this->label = (string)($data['label'] ?? '');
        $this->type = (string)($data['type'] ?? 'text');
        $this->isName = (bool)($data['is_name'] ?? '');
        $this->required = (bool)($data['required'] ?? false);
        $this->showInList = (bool)($data['show_in_list'] ?? false);
        $this->showInView = (bool)($data['show_in_view'] ?? false);
        $this->adminGrid = (bool)($data['admin_grid'] ?? false);
        $this->adminGridHidden = (bool)($data['admin_grid_hidden'] ?? false);
        $this->adminGridFilter = (bool)($data['admin_grid_filter'] ?? false);
        $this->note = (string)($data['note'] ?? '');
        $this->tooltip = (string)($data['tooltip'] ?? '');
        $this->defaultValue = (string)($data['default_value'] ?? '');
        $this->fullText = (bool)($data['full_text'] ?? false);
        $this->expanded = (bool)($data['expanded'] ?? false);
        $this->options = array_map(
            function ($option) {
                return $this->optionFactory->create($this, $option);
            },
            $data['_options'] ?? []
        );
        $this->serialized = array_map(
            function ($serialized) {
                return $this->serializedFactory->create($this, $serialized);
            },
            $data['_serialized'] ?? []
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
        return $this->isName;
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required && $this->getTypeInstance()->isCanBeRequired();
    }

    /**
     * @return bool
     */
    public function isShowInList(): bool
    {
        return $this->getEntity()->isFrontendList() && $this->showInList;
    }

    /**
     * @return bool
     */
    public function isShowInView(): bool
    {
        return $this->getEntity()->isFrontendView() && $this->showInView;
    }

    /**
     * @return bool
     */
    public function isAdminGrid(): bool
    {
        return $this->adminGrid && $this->getTypeInstance()->isCanShowInGrid();
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
        return $this->isAdminGrid() && $this->adminGridFilter && $this->getTypeInstance()->isCanFilterInGrid();
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
     * @return Option[]
     */
    public function getOptions(): array
    {
        return $this->isManualOptions() ? $this->options : [];
    }

    /**
     * @return bool
     * TODO: refactor this to use type instance
     */
    public function isSerialized(): bool
    {
        return $this->type === 'serialized';
    }

    /**
     * @return Serialized[]
     */
    public function getSerialized(): array
    {
        return $this->isSerialized() ? $this->serialized : [];
    }

    /**
     * @return Serialized[]
     * //TODO: refactor to loop only one throught all serialized
     */
    public function getSerializedWithOptions(): array
    {
        return array_filter(
            $this->serialized,
            function (Serialized $serialized) {
                return $serialized->isManualOptions();
            }
        );
    }

    /**
     * @return bool
     */
    public function isFullText(): bool
    {
        return $this->getTypeInstance()->isFullText() && $this->fullText;
    }

    /**
     * @return bool
     */
    public function isExpanded(): bool
    {
        return $this->expanded;
    }

    /**
     * @return AttributeType
     */
    public function getTypeInstance() : AttributeType
    {
        if ($this->typeInstance === null) {
            $this->typeInstance = $this->typeFactory->create($this);
        }
        return $this->typeInstance;
    }

    /**
     * @return array
     * @deprecated
     */
    public function getProcessedOptions() : array
    {
        if ($this->processedOptions === null) {
            $this->processedOptions = [];
            $options = $this->getOptions();
            if ($options != null) {
                $options = explode(self::OPTIONS_DELIMITER, $options);
                foreach ($options as $key => $option) {
                    $option = trim($option);
                    $this->processedOptions[$this->toConstantName($option)] = [
                        'value' => $key + 1,
                        'label' => $option
                    ];
                }
            }
        }
        return $this->processedOptions;
    }

    /**
     * transform string to constant name
     *
     * @param string $string
     * @return string
     */
    protected function toConstantName($string) : string
    {
        $string = str_replace(' ', '_', $string);
        $processed =  preg_replace(
            '/[^A-Za-z0-9_]/',
            '',
            $string
        );
        $processed = strtoupper($processed);
        if (strlen($processed) == 0) {
            return '_EMPTY';
        }
        $first = substr($processed, 0, 1);
        if (is_numeric($first)) {
            $processed = '_' . $processed;
        }
        return $processed;
    }

    /**
     * @return bool
     */
    public function isUpload(): bool
    {
        return $this->getTypeInstance()->isUpload();
    }

    /**
     * @return bool
     * TODO: refactor this to use type instance methods
     */
    public function isManualOptions(): bool
    {
        return $this->type === 'dropdown' || $this->type === 'multiselect';
    }

    /**
     * @return string
     * //TODO: refactor this to loop only once
     */
    public function getOptionType(): string
    {
        foreach ($this->getOptions() as $option) {
            if (!is_numeric($option->getValue())) {
                return 'string';
            }
        }
        return 'number';
    }

    /**
     * @return string
     */
    public function getOptionSourceVirtualType(): string
    {
        $parts = [
            $this->getEntity()->getModule()->getModuleName(),
            $this->getEntity()->getNameSingular(),
            'Source',
            $this->getCode()
        ];
        return $this->stringUtil->glueClassParts($parts, '');
    }

    /**
     * @return bool
     */
    public function isDataProcessorRequired(): bool
    {
        return $this->getTypeInstance()->isDataProcessorRequired();
    }

    /**
     * @return bool
     */
    public function isMultiple(): bool
    {
        return $this->getTypeInstance()->isMultiple();
    }

    /**
     * @return bool
     */
    public function isProductAttribute(): bool
    {
        return $this->getTypeInstance()->isProductAttribute();
    }

    /**
     * @return bool
     */
    public function isProductAttributeSet(): bool
    {
        return $this->getTypeInstance()->isProductAttributeSet();
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
            'show_in_list' => $this->showInList,
            'show_in_view' => $this->showInView,
            'admin_grid' => $this->adminGrid,
            'admin_grid_hidden' => $this->adminGridHidden,
            'admin_grid_filter' => $this->adminGridFilter,
            'note' => $this->note,
            'tooltip' => $this->tooltip,
            'default_value' => $this->defaultValue,
            'full_text' => $this->fullText,
            'expanded' => $this->expanded,
            '_options' => array_map(
                function (Option $option) {
                    return $option->toArray();
                },
                $this->options
            ),
            '_serialized' => array_map(
                function (Serialized $serialized) {
                    return $serialized->toArray();
                },
                $this->serialized
            ),
        ];
    }
}
