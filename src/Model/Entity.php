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

use App\Util\Sorter;

class Entity extends AbstractModel
{
    const LABEL_SINGULAR = 'label_singular';
    const LABEL_PLURAL = 'label_plural';
    const NAME_SINGULAR = 'name_singular';
    const NAME_PLURAL = 'name_plural';
    const ADD_CREATED_TO_GRID = 'add_created_to_grid';
    const ADD_UPDATED_TO_GRID = 'add_updated_to_grid';
    const SEARCH = 'search';
    const STORE = 'store';
    const FRONTEND_LIST = 'frontend_list';
    const FRONTEND_VIEW = 'frontend_view';
    const SEO = 'seo';
    const POSITION = 'position';
    const MENU_LINK = 'menu_link';
    const IS_TREE  = 'is_tree';
    const MENU_LINK_NO_LINK = 0;
    const MENU_LINK_MAIN_MENU = 1;
    const MENU_LINK_FOOTER = 2;
    /**
     * @var Sorter
     */
    private $sorter;
    /**
     * @var bool
     */
    private $needsAttributeSort = false;

    /**
     * Entity constructor.
     * @param Sorter $sorter
     * @param array $data
     */
    public function __construct(Sorter $sorter, array $data = [])
    {
        $this->sorter = $sorter;
        parent::__construct($data);
    }

    /**
     * @var array
     */
    protected $propertyNames = [
        self::LABEL_SINGULAR, self::LABEL_PLURAL, self::NAME_SINGULAR, self::NAME_PLURAL,
        self::ADD_CREATED_TO_GRID, self::ADD_UPDATED_TO_GRID, self::SEARCH, self::STORE,
        self::FRONTEND_LIST, self::FRONTEND_VIEW, self::SEO, self::POSITION, self::MENU_LINK,
        self::IS_TREE
    ];
    /**
     * @var Attribute[]
     */
    private $attributes = [];

    /**
     * @var Module
     */
    private $module;

    /**
     * @return Attribute[]
     */
    public function getAttributes(): array
    {
        if ($this->needsAttributeSort) {
            $this->attributes = $this->sorter->sort($this->attributes);
            $this->needsAttributeSort = false;
        }
        return $this->attributes;
    }

    /**
     * @param Attribute $attribute
     */
    public function addAttribute(Attribute $attribute)
    {
        $attribute->setEntity($this);
        $this->attributes[] = $attribute;
        $this->needsAttributeSort = true;
    }

    /**
     * @return Module
     */
    public function getModule(): ?Module
    {
        return $this->module;
    }

    /**
     * @param Module $module
     */
    public function setModule(Module $module) : void
    {
        $this->module = $module;
    }

    /**
     * @return null|string
     */
    public function getLabelSingular() : ?string
    {
        return $this->getData(self::LABEL_SINGULAR);
    }

    /**
     * @return null|string
     */
    public function getLabelPlural() : ?string
    {
        return $this->getData(self::LABEL_PLURAL);
    }

    /**
     * @return null|string
     */
    public function getNameSingular() : ?string
    {
        return $this->getData(self::NAME_SINGULAR);
    }

    /**
     * @return null|string
     */
    public function getNamePlural() : ?string
    {
        return $this->getData(self::NAME_PLURAL);
    }

    /**
     * @return bool
     */
    public function getAddCreatedToGrid() : bool
    {
        return (bool)$this->getData(self::ADD_CREATED_TO_GRID);
    }

    /**
     * @return bool
     */
    public function getAddUpdatedToGrid() : bool
    {
        return (bool)$this->getData(self::ADD_UPDATED_TO_GRID);
    }

    /**
     * @return bool
     */
    public function getSearch() : bool
    {
        return (bool)$this->getData(self::SEARCH);
    }

    /**
     * @return bool
     */
    public function getStore() : bool
    {
        return (bool)$this->getData(self::STORE);
    }

    /**
     * @return bool
     */
    public function getFrontendList() : bool
    {
        return (bool)$this->getData(self::FRONTEND_LIST);
    }

    /**
     * @return bool
     */
    public function getFrontendView() : bool
    {
        return (bool)$this->getData(self::FRONTEND_VIEW);
    }

    /**
     * @return bool
     */
    public function getSeo() : bool
    {
        return (bool)$this->getData(self::SEO);
    }

    /**
     * @return int
     */
    public function getPosition() : int
    {
        return (int)$this->getData(self::POSITION);
    }

    /**
     * @return Attribute|null
     */
    public function getNameAttribute() : ?Attribute
    {
        foreach ($this->getAttributes() as $attribute) {
            if ($attribute->getIsName()) {
                return $attribute;
            }
        }
        return null;
    }

    /**
     * @return array
     */
    protected function getAdditionalToArray(): array
    {
        $result = [];
        $result['_attributes'] = array_map(
            function (Attribute $item) {
                return $item->toArray();
            },
            $this->getAttributes()
        );
        return $result;
    }

    /**
     * @param $type
     * @return bool
     */
    public function hasAttributeType($type) : bool
    {
        foreach ($this->getAttributes() as $attribute) {
            if ($attribute->getType() === $type) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param string $typeConfig
     * @return Attribute[]
     */
    public function getAttributesWithTypeConfig(string $typeConfig) : array
    {
        return array_values(
            array_filter(
                $this->getAttributes(),
                function (Attribute $item) use ($typeConfig) {
                    return $item->getTypeInstance()->getData($typeConfig);
                }
            )
        );
    }

    /**
     * @param string $typeConfig
     * @return Attribute[]
     */
    public function getAttributesWithType(string $type) : array
    {
        return array_values(
            array_filter(
                $this->getAttributes(),
                function (Attribute $item) use ($type) {
                    return $item->getType() === $type;
                }
            )
        );
    }

    /**
     * @param string $typeConfig
     * @param int $tabs
     * @return string
     */
    public function getAttributesWithTypeConfigString(string $typeConfig, int $tabs = 0) : string
    {
        return $this->arrayToPrintString(
            $this->getAttributeCodes($this->getAttributesWithTypeConfig($typeConfig)),
            $tabs
        );
    }

    /**
     * @param string $type
     * @param int $tabs
     * @return string
     */
    public function getAttributesWithTypeString(string $type, int $tabs = 0) : string
    {
        return $this->arrayToPrintString(
            $this->getAttributeCodes($this->getAttributesWithType($type)),
            $tabs
        );
    }

    /**
     * @param Attribute[] $attributes
     * @return array
     */
    private function getAttributeCodes(array $attributes) : array
    {
        return array_map(
            function (Attribute $item) {
                return $item->getCode();
            },
            $attributes
        );
    }

    /**
     * @param array $codes
     * @param int $tabs
     * @return string
     */
    private function arrayToPrintString($codes, $tabs = 0) : string
    {
        $pad = str_repeat(' ', 4 * $tabs);
        return (count($codes)) ? $pad . "'" . implode("'," . PHP_EOL . $pad . "'", $codes) . "'" : '';
    }

    /**
     * @return int
     */
    public function getMenuLink() : int
    {
        if (!$this->getFrontendList()) {
            return self::MENU_LINK_NO_LINK;
        }
        $menuLink = (int)$this->getData(self::MENU_LINK);
        $allowed = [self::MENU_LINK_NO_LINK, self::MENU_LINK_MAIN_MENU, self::MENU_LINK_FOOTER];
        return (in_array($menuLink, $allowed)) ? $menuLink : self::MENU_LINK_NO_LINK;
    }

    /**
     * @return bool
     */
    public function hasFrontend()
    {
        return $this->getFrontendList() || $this->getFrontendView();
    }

    /**
     * @return bool
     */
    public function getIsTree() : bool
    {
        return (bool)$this->getData(self::IS_TREE);
    }
}
