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

class Entity extends AbstractModel
{
    /**
     * @var array
     */
    protected $propertyNames = [
        'label_singular', 'label_plural', 'name_singular', 'name_plural',
        'type', 'is_tree', 'add_created_to_grid',
        'add_updated_to_grid', 'search'
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
        $this->sortAttributes();
        return $this->attributes;
    }

    /**
     * sort attributes
     */
    private function sortAttributes()
    {
        uasort(
            $this->attributes,
            function (Attribute $attributeA, Attribute $attributeB) {
                $sortOrderA = $attributeA->getData('position');
                $sortOrderB = $attributeB->getData('position');
                if ($sortOrderA === $sortOrderB) {
                    return 0;
                }
                // empty values are placed at the end
                if ($sortOrderB === "") {
                    return -1;
                }
                if ($sortOrderA === "") {
                    return 1;
                }
                return ((int)$sortOrderA < (int)$sortOrderB) ? -1 : 1;
            }
        );
        $this->attributes = array_values($this->attributes);
    }

    /**
     * @param Attribute $attribute
     */
    public function addAttribute(Attribute $attribute)
    {
        $attribute->setEntity($this);
        $this->attributes[] = $attribute;
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
     * Temprorary. MVP won't support EAV entities but let's create support for entity types
     * @param $key
     * @param null|string $default
     * @return string|null
     */
    public function getData(string $key, ?string $default = null) : ?string
    {
        if ($key === 'type') {
            return "1";
        }
        return parent::getData($key, $default);
    }

    /**
     * @return Attribute|null
     */
    public function getNameAttribute() : ?Attribute
    {
        foreach ($this->getAttributes() as $attribute) {
            if ($attribute->getData('is_name')) {
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
            if ($attribute->getData('type') == $type) {
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
        return array_values(array_filter(
            $this->getAttributes(),
            function (Attribute $item) use ($typeConfig) {
                return $item->getTypeInstance()->getData($typeConfig);
            }
        ));
    }

    /**
     * @param string $typeConfig
     * @return Attribute[]
     */
    public function getAttributesWithType(string $type) : array
    {
        return array_values(array_filter(
            $this->getAttributes(),
            function (Attribute $item) use ($type) {
                return $item->getData('type') === $type;
            }
        ));
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
                return $item->getData('code');
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
}
