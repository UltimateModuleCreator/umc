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

use App\Service\Form\OptionProvider\FrontendMenuLink;
use App\Util\StringUtil;

class Entity
{
    /**
     * @var StringUtil
     */
    private $stringUtil;
    /**
     * @var AttributeFactory
     */
    private $attributeFactory;
    /**
     * @var string
     */
    private $labelSingular;
    /**
     * @var string
     */
    private $labelPlural;
    /**
     * @var string
     */
    private $nameSingular;
    /**
     * @var string
     */
    private $namePlural;
    /**
     * @var bool
     */
    private $search;
    /**
     * @var bool
     */
    private $store;
    /**
     * @var bool
     */
    private $frontendList;
    /**
     * @var bool
     */
    private $frontendView;
    /**
     * @var bool
     */
    private $seo;
    /**
     * @var int
     */
    private $menuLink;
    /**
     * @var Attribute[]
     */
    private $attributes = [];
    /**
     * @var Module
     */
    private $module;

    /**
     * Entity constructor.
     * @param StringUtil $stringUtil
     * @param AttributeFactory $attributeFactory
     * @param Module $module
     * @param array $data
     */
    public function __construct(
        StringUtil $stringUtil,
        AttributeFactory $attributeFactory,
        Module $module,
        array $data = []
    ) {
        $this->module = $module;
        $this->stringUtil = $stringUtil;
        $this->attributeFactory = $attributeFactory;
        $this->nameSingular = (string)($data['name_singular'] ?? '');
        $this->namePlural = (string)($data['name_plural'] ?? '');
        $this->labelSingular = (string)($data['label_singular'] ?? '');
        $this->labelPlural = (string)($data['label_plural'] ?? '');
        $this->search = (bool)($data['search'] ?? false);
        $this->frontendList = (bool)($data['frontend_list'] ?? false);
        $this->frontendView = (bool)($data['frontend_view'] ?? false);
        $this->seo = (bool)($data['seo'] ?? false);
        $this->menuLink = (int)($data['menu_link'] ?? FrontendMenuLink::NONE);
        $this->attributes = array_map(
            function (array $attribute) {
                return $this->attributeFactory->create($this, $attribute);
            },
            $data['_attributes'] ?? []
        );
    }

    /**
     * @return Attribute[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @return Module
     */
    public function getModule(): Module
    {
        return $this->module;
    }

    /**
     * @return null|string
     */
    public function getLabelSingular(): string
    {
        return $this->getLabelSingular();
    }

    /**
     * @return null|string
     */
    public function getLabelPlural(): string
    {
        return $this->labelPlural;
    }

    /**
     * @return string
     */
    public function getNameSingular(): string
    {
        return $this->nameSingular;
    }

    /**
     * @return string
     */
    public function getNamePlural(): string
    {
        return $this->namePlural;
    }

    /**
     * @return bool
     */
    public function isSearch(): bool
    {
        return $this->search;
    }

    /**
     * @return bool
     */
    public function isStore(): bool
    {
        return $this->store;
    }

    /**
     * @return bool
     */
    public function isFrontendList(): bool
    {
        return $this->frontendList;
    }

    /**
     * @return bool
     */
    public function isFrontendView(): bool
    {
        return $this->frontendView;
    }

    /**
     * @return bool
     */
    public function isSeo(): bool
    {
        return $this->seo;
    }

    /**
     * @return Attribute|null
     */
    public function getNameAttribute(): ?Attribute
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
    public function toArray(): array
    {
        return [
            'name_singular' => $this->nameSingular,
            'name_plural' => $this->namePlural,
            'label_singular' => $this->labelSingular,
            'label_plural' => $this->labelPlural,
            'search' => $this->search,
            'frontend_list' => $this->frontendList,
            'frontend_view' => $this->frontendView,
            'seo' => $this->seo,
            'menu_link' => $this->menuLink,
            '_attributes' => array_map(
                function (Attribute $attribute) {
                    return $attribute->toArray();
                },
                $this->getAttributes()
            )
        ];
    }

    /**
     * @param $type
     * @return bool
     */
    public function hasAttributeType($type): bool
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
     * @deprecated
     */
    public function getAttributesWithTypeConfig(string $typeConfig): array
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
     * @deprecated
     */
    public function getAttributesWithType(string $type): array
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
     * @deprecated
     */
    public function getAttributesWithTypeConfigString(string $typeConfig, int $tabs = 0): string
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
     * @deprecated
     */
    public function getAttributesWithTypeString(string $type, int $tabs = 0): string
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
    private function getAttributeCodes(array $attributes): array
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
    private function arrayToPrintString($codes, $tabs = 0): string
    {
        $pad = str_repeat(' ', 4 * $tabs);
        return (count($codes)) ? $pad . "'" . implode("'," . PHP_EOL . $pad . "'", $codes) . "'" : '';
    }

    /**
     * @return int
     */
    public function getMenuLink(): int
    {
        if (!$this->isFrontendList()) {
            return FrontendMenuLink::NONE;
        }
        return $this->menuLink;
    }

    /**
     * @return bool
     */
    public function isFrontend(): bool
    {
        return $this->isFrontendList() || $this->isFrontendView();
    }

    /**
     * @return string
     */
    public function getMainTableName(): string
    {
        $parts = [];
        $parts[] = $this->getModule()->getModuleName();
        $parts[] = $this->getNameSingular();
        return $this->stringUtil->camel(implode('_', $parts));
    }

    /**
     * @return string
     */
    public function getStoreTableName(): string
    {
        return $this->getMainTableName() . '_store';
    }
}
