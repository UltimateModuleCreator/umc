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
use App\Model\Attribute\TypeFactory;
use App\Model\Attribute\TypeInterface;

class Attribute
{
    const OPTIONS_DELIMITER = "\n";
    const CODE = 'code';
    const LABEL = 'label';
    const TYPE = 'type';
    const IS_NAME = 'is_name';
    const REQUIRED = 'required';
    const OPTIONS = 'options';
    const POSITION = 'position';
    const NOTE = 'note';
    const ADMIN_GRID = 'admin_grid';
    const ADMIN_GRID_FILTER = 'admin_grid_filter';
    const DEFAULT_VALUE = 'default_value';
    const SHOW_IN_LIST = 'show_in_list';
    const SHOW_IN_VIEW = 'show_in_view';
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
     * Attribute constructor.
     * @param AttributeTypeFactory $typeFactory
     * @param OptionFactory $optionFactory
     * @param SerializedFactory $serializedFactory
     * @param Entity $entity
     * @param array $data
     */
    public function __construct(
        AttributeTypeFactory $typeFactory,
        OptionFactory $optionFactory,
        SerializedFactory $serializedFactory,
        Entity $entity,
        array $data = []
    ) {
        $this->typeFactory = $typeFactory;
        $this->optionFactory = $optionFactory;
        $this->serializedFactory = $serializedFactory;
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
        return $this->adminGrid && $this->getTypeInstance()->isCanShowInAdminGrid();
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
        return $this->isAdminGrid() && $this->adminGridFilter && $this->getTypeInstance()->isCanFilterInAdminGrid();
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
        return $this->getTypeInstance()->getDefaultValue();
    }

    /**
     * @return Option[]
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @return Serialized[]
     */
    public function getSerialized(): array
    {
        return $this->serialized;
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
            '_options' => array_map(
                function (Option $option) {
                    return $option->toArray();
                },
                $this->getOptions()
            ),
            '_serialized' => array_map(
                function (Serialized $serialized) {
                    return $serialized->toArray();
                },
                $this->getSerialized()
            ),
        ];
    }
}
