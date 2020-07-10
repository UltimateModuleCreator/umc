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

use App\Umc\CoreBundle\Model\Attribute\Factory as AttributeFactory;
use App\Umc\CoreBundle\Util\StringUtil;

class Entity
{
    /**
     * @var StringUtil
     */
    protected $stringUtil;
    /**
     * @var AttributeFactory
     */
    protected $attributeFactory;
    /**
     * @var string
     */
    protected $labelSingular;
    /**
     * @var string
     */
    protected $labelPlural;
    /**
     * @var string
     */
    protected $nameSingular;
    /**
     * @var string
     */
    protected $namePlural;
    /**
     * @var bool
     */
    protected $search;
    /**
     * @var bool
     */
    protected $frontend;
    /**
     * @var bool
     */
    protected $seo;
    /**
     * @var bool bool
     */
    protected $store;
    /**
     * @var bool;
     */
    protected $topMenu;
    /**
     * @var bool
     */
    protected $footerLinks;
    /**
     * @var Attribute[]
     */
    protected $attributes = [];
    /**
     * @var Module
     */
    protected $module;
    /**
     * @var array
     */
    protected $cacheData = [];
    /**
     * @var array
     */
    protected $attributeCache;
    /**
     * @var string[]
     */
    protected $flags;
    /**
     * @var array
     */
    protected $attributesByFlagPrefix = [];
    /**
     * @var Entity[]
     */
    private $parentEntities;
    /**
     * @var Relation[]
     */
    private $parentRelations;
    /**
     * @var Entity[]
     */
    private $childEntities;
    /**
     * @var Relation[]
     */
    private $childRelations;
    /**
     * @var Entity[]
     */
    private $siblingEntities;
    /**
     * @var Relation[]
     */
    private $siblingRelations;

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
        $this->frontend = (bool)($data['frontend'] ?? false);
        $this->seo = (bool)($data['seo'] ?? false);
        $this->store = (bool)($data['store'] ?? false);
        $this->topMenu = (bool)($data['top_menu'] ?? false);
        $this->footerLinks = (bool)($data['footer_links'] ?? false);
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
        return $this->labelSingular;
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
    public function isSeo(): bool
    {
        return $this->isFrontend() && $this->seo;
    }

    /**
     * @return bool
     */
    public function isFooterLinks(): bool
    {
        return $this->isFrontend() && $this->footerLinks;
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
    public function isTopMenu(): bool
    {
        return $this->isFrontend() && $this->topMenu;
    }

    /**
     * @return bool
     */
    public function isFrontend(): bool
    {
        return $this->getModule()->isFrontend() && $this->frontend;
    }

    /**
     * @return Attribute|null
     */
    public function getNameAttribute(): ?Attribute
    {
        foreach ($this->getAttributes() as $attribute) {
            if ($attribute->isName()) {
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
            'frontend' => $this->frontend,
            'search' => $this->search,
            'seo' => $this->seo,
            'store' => $this->store,
            'top_menu' => $this->topMenu,
            'footer_links' => $this->footerLinks,
            '_attributes' => array_map(
                function (Attribute $attribute) {
                    return $attribute->toArray();
                },
                $this->attributes
            )
        ];
    }

    /**
     * @return string
     */
    public function getPk(): string
    {
        return $this->stringUtil->snake($this->getNameSingular()) . '_id';
    }

    /**
     * @return void
     */
    private function initAttributeCacheData(): void
    {
        if ($this->attributeCache === null) {
            $this->attributeCache[] = [
                'by_flag' => [],
                'by_type' => []
            ];
            foreach ($this->getAttributes() as $attribute) {
                $this->cacheAttributeData($attribute);
            }
        }
    }

    /**
     * @param Attribute $attribute
     */
    protected function cacheAttributeData(Attribute $attribute)
    {
        $type = $attribute->getType();
        $this->attributeCache['by_type'][$type] = $this->attributeCache['by_type'][$type] ?? [];
        $this->attributeCache['by_type'][$type][] = $attribute;
        foreach ($attribute->getFlags() as $flag) {
            $this->attributeCache['by_flag'][$flag] = $this->attributeCache['by_flag'][$flag] ?? [];
            $this->attributeCache['by_flag'][$flag][] = $attribute;
        }
    }

    /**
     * @param $flag
     * @return Attribute[]
     */
    public function getAttributesWithFlag($flag)
    {
        $this->initAttributeCacheData();
        return $this->attributeCache['by_flag'][$flag] ?? [];
    }

    /**
     * @param $flag
     * @return bool
     */
    public function hasAttributesWithFlag($flag): bool
    {
        return count($this->getAttributesWithFlag($flag)) > 0;
    }

    /**
     * @return array
     */
    public function getFlags()
    {
        if ($this->flags === null) {
            $this->flags = [];
            $this->isSearch() && $this->flags[] = 'search';
            $this->isFrontend() && $this->flags[] = 'frontend';
            $this->isStore() && $this->flags[] = 'store';
            $this->isFooterLinks() && $this->flags[] = 'footer_links';
            $this->isTopMenu() && $this->flags[] = 'top_menu';
            $this->initAttributeCacheData();
            $this->flags = array_merge(
                $this->flags,
                array_map(
                    function (string $flag) {
                        return 'attribute_' . $flag;
                    },
                    array_keys($this->attributeCache['by_flag'])
                )
            );
            $this->flags = array_merge(
                $this->flags,
                array_map(
                    function (string $flag) {
                        return 'attribute_type_' . $flag;
                    },
                    array_keys($this->attributeCache['by_type'])
                )
            );
        }
        return $this->flags;
    }

    /**
     * @param string $type
     * @return Attribute[]
     */
    public function getAttributesWithType(string $type): array
    {
        $this->initAttributeCacheData();
        return $this->attributeCache['by_type'][$type] ?? [];
    }

    /**
     * @param string $type
     * @return bool
     */
    public function hasAttributesWithType(string $type): bool
    {
        return count($this->getAttributesWithType($type)) > 0;
    }

    /**
     * @param $prefix
     * @return Attribute[][]
     */
    public function getAttributesWithFlagPrefix($prefix): array
    {
        if (!isset($this->attributesByFlagPrefix[$prefix])) {
            $this->attributesByFlagPrefix[$prefix] = [];
            foreach ($this->getAttributes() as $attribute) {
                $suffixes = $attribute->getFlagSuffixes($prefix);
                foreach ($suffixes as $suffix) {
                    $this->attributesByFlagPrefix[$prefix][$suffix] =
                        $this->attributesByFlagPrefix[$prefix][$suffix] ?? [];
                    $this->attributesByFlagPrefix[$prefix][$suffix][] = $attribute;
                }
            }
        }
        return $this->attributesByFlagPrefix[$prefix];
    }

    /**
     * @return Relation[]
     */
    public function getParentRelations()
    {
        $this->parseRelations();
        return $this->parentRelations;
    }

    /**
     * @return Relation[]
     */
    public function getChildRelations(): array
    {
        $this->parseRelations();
        return $this->childRelations;
    }

    /**
     * @return Relation[]
     */
    public function getSiblingRelations(): array
    {
        $this->parseRelations();
        return $this->siblingRelations;
    }

    /**
     * loop through all relations abd map out the related entities
     */
    private function parseRelations()
    {
        if ($this->parentRelations === null) {
            $this->parentRelations = [];
            $this->parentEntities = [];
            $this->childEntities = [];
            $this->childRelations = [];
            $this->siblingEntities = [];
            $this->siblingRelations = [];
            foreach ($this->getModule()->getRelations() as $relation) {
                if ($relation->getEntityOneInstance() === $this) {
                    if ($relation->getType() === Relation::TYPE_ONE_TO_MANY) {
                        $this->childRelations[] = $relation;
                        $childEntity = $relation->getRelatedEntity($this);
                        $this->childEntities[$childEntity->getNameSingular()] = $childEntity;
                    } elseif ($relation->getType() === Relation::TYPE_MANY_TO_MANY) {
                        $siblingEntity = $relation->getRelatedEntity($this);
                        $this->siblingRelations[] = $relation;
                        $this->siblingEntities[$siblingEntity->getNameSingular()] = $siblingEntity;
                    }
                }
                if ($relation->getEntityTwoInstance() === $this) {
                    if ($relation->getType() === Relation::TYPE_ONE_TO_MANY) {
                        $this->parentRelations[] = $relation;
                        $parentEntity = $relation->getRelatedEntity($this);
                        $this->parentEntities[$parentEntity->getNameSingular()] = $parentEntity;
                    } elseif ($relation->getType() === Relation::TYPE_MANY_TO_MANY) {
                        $siblingEntity = $relation->getRelatedEntity($this);
                        $this->siblingRelations[] = $relation;
                        $this->siblingEntities[$siblingEntity->getNameSingular()] = $siblingEntity;
                    }
                }
            }
        }
    }

    /**
     * @return Entity[]
     */
    public function getParentEntities(): array
    {
        $this->parseRelations();
        return $this->parentEntities;
    }

    /**
     * @return Entity[]
     */
    public function getChildEntities(): array
    {
        $this->parseRelations();
        return $this->childEntities;
    }

    /**
     * @return Entity[]
     */
    public function getSiblingEntities(): array
    {
        $this->parseRelations();
        return $this->siblingEntities;
    }
}
