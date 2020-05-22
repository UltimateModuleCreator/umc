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
            'search' => $this->search,
            'seo' => $this->seo,
            '_attributes' => array_map(
                function (Attribute $attribute) {
                    return $attribute->toArray();
                },
                $this->attributes
            )
        ];
    }

    /**
     * @return void
     */
    private function initAttributeCacheData(): void
    {
        if (isset($this->cacheData['attribute'])) {
            return;
        }
        $this->cacheData['attribute']['by_type'] = [];
        $this->cacheData['attribute']['upload'] = [];
        $this->cacheData['attribute']['with_options'] = [];
        $this->cacheData['attribute']['codes'] = [];
        $this->cacheData['attribute']['data_processor_required'] = [];
        $this->cacheData['attribute']['product_attribute'] = [];
        $this->cacheData['attribute']['product_attribute_set'] = [];
        $this->cacheData['attribute']['full_text'] = [];
        $this->cacheData['attribute']['show_in_list'] = [];
        $this->cacheData['attribute']['show_in_view'] = [];
        foreach ($this->getModule()->getProcessorTypes() as $processorType) {
            $this->cacheData['attribute']['processor'][$processorType] = [];
        }
        foreach ($this->getAttributes() as $attribute) {
            $type = $attribute->getType();
            $this->cacheData['attribute']['by_type'][$type] = $this->cacheData['attribute']['by_type'][$type] ?? [];
            $this->cacheData['attribute']['by_type'][$type][] = $attribute;
            $this->cacheData['attribute']['codes'][] = $attribute->getCode();
            $attribute->isUpload() && ($this->cacheData['attribute']['upload'][] = $attribute);
            $attribute->isShowInList() && ($this->cacheData['attribute']['show_in_list'][] = $attribute);
            $attribute->isShowInView() && ($this->cacheData['attribute']['show_in_view'][] = $attribute);
            $attribute->isManualOptions() && ($this->cacheData['attribute']['with_options'][] = $attribute);
            $attribute->isProductAttribute() && ($this->cacheData['attribute']['product_attribute'][] = $attribute);
            $attribute->isProductAttributeSet()
            && ($this->cacheData['attribute']['product_attribute_set'][] = $attribute);
            $attribute->isFullText() && ($this->cacheData['attribute']['full_text'][] = $attribute);
            foreach ($this->getModule()->getProcessorTypes() as $processorType) {
                $processors = $attribute->getProcessorTypes($processorType);
                foreach ($processors as $processor) {
                    $this->cacheData['attribute']['processor'][$processorType][$processor] =
                        $this->cacheData['attribute']['processor'][$processorType][$processor] ?? [];
                    $this->cacheData['attribute']['processor'][$processorType][$processor][] = $attribute;
                }
            }
        }
    }

    /**
     * @return bool
     */
    public function isProductAttribute(): bool
    {
        $this->initAttributeCacheData();
        return count($this->cacheData['attribute']['product_attribute']) > 0;
    }

    /**
     * @param $type
     * @return Attribute[][]
     */
    public function getAttributesWithProcessor($type): array
    {
        $this->initAttributeCacheData();
        return $this->cacheData['attribute']['processor'][$type] ?? [];
    }

    /**
     * @param $processor
     * @param $processorType
     * @return Attribute[]
     */
    public function getAttributesWithProcessorType($processor, $processorType): array
    {
        $this->initAttributeCacheData();
        return $this->cacheData['attribute']['processor'][$processor][$processorType] ?? [];
    }

    /**
     * @return Attribute[]
     */
    public function getListAttributes()
    {
        $this->initAttributeCacheData();
        return $this->cacheData['attribute']['show_in_list'];
    }

    /**
     * @return Attribute[]
     */
    public function getViewAttributes()
    {
        $this->initAttributeCacheData();
        return $this->cacheData['attribute']['show_in_view'];
    }

    /**
     * @return array
     */
    public function getFullTextAttributes(): array
    {
        $this->initAttributeCacheData();
        return $this->cacheData['attribute']['full_text'];
    }

    /**
     * @return bool
     */
    public function isProductAttributeSet(): bool
    {
        $this->initAttributeCacheData();
        return count($this->cacheData['attribute']['product_attribute_set']) > 0;
    }

    /**
     * @param $type
     * @return bool
     */
    public function hasAttributeType($type): bool
    {
        $this->initAttributeCacheData();
        return isset($this->cacheData['attribute']['by_type'][$type]);
    }

    /**
     * @return bool
     */
    public function isUpload(): bool
    {
        $this->initAttributeCacheData();
        return count($this->cacheData['attribute']['upload']) > 0;
    }

    /**
     * @return Attribute[]
     */
    public function getOptionAttributes(): array
    {
        $this->initAttributeCacheData();
        return $this->cacheData['attribute']['with_options'];
    }

    /**
     * @return bool
     */
    public function hasOptionAttributes(): bool
    {
        return count($this->getOptionAttributes()) > 0;
    }

    /**
     * @param string $type
     * @return Attribute[]
     */
    public function getAttributesWithType(string $type): array
    {
        $this->initAttributeCacheData();
        return $this->cacheData['attribute']['by_type'][$type] ?? [];
    }

    /**
     * @return bool
     */
    public function isFrontend(): bool
    {
        return $this->module->isFrontend() && $this->frontend;
    }

    /**
     * @return bool
     */
    public function hasImageAttributes(): bool
    {
        return count($this->getImageAttributes()) > 0;
    }

    /**
     * @return bool
     */
    public function hasFileAttributes(): bool
    {
        return count($this->getFileAttributes()) > 0;
    }

    /**
     * @return Attribute[]
     */
    public function getImageAttributes(): array
    {
        return $this->getAttributesWithType('image');
    }

    /**
     * @return Attribute[]
     */
    public function getFileAttributes(): array
    {
        return $this->getAttributesWithType('file');
    }
}
