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

use App\Model\Attribute\TypeFactory;
use App\Model\Attribute\TypeInterface;

class Attribute extends AbstractModel
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
     * @var TypeFactory
     */
    private $typeFactory;
    /**
     * @var Entity
     */
    private $entity;
    /**
     * @var array
     */
    private $processedOptions;

    /**
     * Attribute constructor.
     * @param TypeFactory $typeFactory
     * @param array $data
     */
    public function __construct(TypeFactory $typeFactory, array $data = [])
    {
        $this->typeFactory = $typeFactory;
        parent::__construct($data);
    }

    /**
     * @var array
     */
    protected $propertyNames = [
        'code', 'label', 'type', 'is_name', 'required',
        'options', 'position', 'note', 'admin_grid',
        'admin_grid_filter', 'default_value',
        'show_in_list', 'show_in_view'
    ];

    /**
     * @return Entity
     */
    public function getEntity() : Entity
    {
        return $this->entity;
    }

    /**
     * @param Entity $entity
     */
    public function setEntity(Entity $entity) : void
    {
        $this->entity = $entity;
    }

    /**
     * @return TypeInterface
     * @throws \Exception
     */
    public function getTypeInstance() : TypeInterface
    {
        if ($this->typeInstance === null) {
            $this->typeInstance = $this->typeFactory->create($this);
        }
        return $this->typeInstance;
    }

    /**
     * @return null|string
     */
    public function getCode() : ?string
    {
        return $this->getData(self::CODE);
    }

    /**
     * @return null|string
     */
    public function getLabel() : ?string
    {
        return $this->getData(self::LABEL);
    }

    /**
     * @return null|string
     */
    public function getType() : ?string
    {
        return $this->getData(self::TYPE);
    }

    /**
     * @return bool
     */
    public function getIsName() : bool
    {
        return (bool)$this->getData(self::IS_NAME);
    }

    /**
     * @return bool
     */
    public function getRequired() : bool
    {
        return (bool)$this->getData(self::REQUIRED);
    }

    /**
     * @return null|string
     */
    public function getOptions() : ?string
    {
        return $this->getData(self::OPTIONS);
    }

    /**
     * @return int
     */
    public function getPosition() : int
    {
        return (int)$this->getData(self::POSITION);
    }

    /**
     * @return null|string
     */
    public function getNote() : ?string
    {
        return $this->getData(self::NOTE);
    }

    /**
     * @return int
     */
    public function getAdminGrid() : int
    {
        return (int)$this->getData(self::ADMIN_GRID);
    }

    /**
     * @return bool
     */
    public function getAdminGridFilter() : bool
    {
        return (bool)$this->getAdminGrid() && (bool)$this->getData(self::ADMIN_GRID_FILTER);
    }

    /**
     * @return null|string
     */
    public function getDefaultValue() : ?string
    {
        return $this->getData(self::DEFAULT_VALUE);
    }

    /**
     * @return bool
     */
    public function getShowInList() : bool
    {
        return $this->getEntity()->getFrontendList() && $this->getData(self::SHOW_IN_LIST);
    }

    /**
     * @return bool
     */
    public function getShowInView() : bool
    {
        return $this->getEntity()->getFrontendView() && $this->getData(self::SHOW_IN_VIEW);
    }

    /**
     * @return array
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
}
