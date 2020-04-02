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
     * @var array
     */
    private $cacheData = [];

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
        $this->store = (bool)($data['store'] ?? false);
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
            'frontend_list' => $this->frontendList,
            'frontend_view' => $this->frontendView,
            'seo' => $this->seo,
            'menu_link' => $this->menuLink,
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
        $this->cacheData['attribute']['multiple'] = [];
        $this->cacheData['attribute']['data_processor_required'] = [];
        $this->cacheData['attribute']['product_attribute'] = [];
        $this->cacheData['attribute']['product_attribute_set'] = [];
        $this->cacheData['attribute']['full_text'] = [];
        foreach ($this->getAttributes() as $attribute) {
            $type = $attribute->getType();
            $this->cacheData['attribute']['by_type'][$type] = $this->cacheData['attribute']['by_type'][$type] ?? [];
            $this->cacheData['attribute']['by_type'][$type][] = $attribute;
            $this->cacheData['attribute']['codes'][] = $attribute->getCode();
            $attribute->isUpload() && ($this->cacheData['attribute']['upload'][] = $attribute);
            $attribute->isManualOptions() && ($this->cacheData['attribute']['with_options'][] = $attribute);
            $attribute->isDataProcessorRequired() &&
                ($this->cacheData['attribute']['data_processor_required'][] = $attribute);
            $attribute->isMultiple() && ($this->cacheData['attribute']['multiple'][] = $attribute);
            $attribute->isProductAttribute() && ($this->cacheData['attribute']['product_attribute'][] = $attribute);
            $attribute->isProductAttributeSet()
                && ($this->cacheData['attribute']['product_attribute_set'][] = $attribute);
            $attribute->isFullText() && ($this->cacheData['attribute']['full_text'][] = $attribute);
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
     * @return Attribute[]
     */
    public function getSerializedAttributes(): array
    {
        $this->initAttributeCacheData();
        return $this->cacheData['attribute']['serialized'] ?? [];
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
        $this->initAttributeCacheData();
        return $this->cacheData['attribute'][$type] ?? [];
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
        return $this->stringUtil->snake(implode('_', $parts));
    }

    /**
     * @return string
     */
    public function getStoreTableName(): string
    {
        return $this->getMainTableName() . '_store';
    }

    /**
     * @return string[]
     */
    public function getComposerDependencies(): array
    {
        if (isset($this->cacheData['composer_dependencies'])) {
            return $this->cacheData['composer_dependencies'];
        }
        $dependencies = [];
        if ($this->isFrontend()) {
            $dependencies[] = 'magento/module-theme';
            if ($this->hasAttributeType('wysiwyg')) {
                $dependencies[] = 'magento/module-widget';
            }
        }
        if ($this->isUpload()) {
            $dependencies[] = "magento/module-media-storage";
            $dependencies[] = "magento/module-store";
        }
        if ($this->isStore()) {
            $dependencies[] = "magento/module-store";
        }
        if ($this->hasAttributeType('product_attribute') || $this->hasAttributeType('product_attribute_set')) {
            $dependencies[] = "magento/module-catalog";
            $dependencies[] = "magento/module-eav";
        }
        $this->cacheData['composer_dependencies'] = $dependencies;
        return $this->cacheData['composer_dependencies'];
    }

    /**
     * @return string[]
     */
    public function getModuleDependencies(): array
    {
        if (isset($this->cacheData['module_dependencies'])) {
            return $this->cacheData['module_dependencies'];
        }
        $dependencies = [];
        if ($this->isFrontend()) {
            $dependencies[] = 'Magento_Theme';
            if ($this->hasAttributeType('wysiwyg')) {
                $dependencies[] = 'Magento_Widget';
            }
        }
        if ($this->isUpload()) {
            $dependencies[] = "Magento_MediaStorage";
            $dependencies[] = "Magento_Store";
        }
        if ($this->isStore()) {
            $dependencies[] = "Magento_Store";
        }
        if ($this->hasAttributeType('product_attribute') || $this->hasAttributeType('product_attribute_set')) {
            $dependencies[] = "Magento_Catalog";
            $dependencies[] = "Magento_Eav";
        }
        $this->cacheData['module_dependencies'] = $dependencies;
        return $this->cacheData['module_dependencies'];
    }

    /**
     * @return string
     */
    public function getAclName(): string
    {
        return $this->module->getAclName() . '_' . $this->stringUtil->snake($this->getNameSingular());
    }

    /**
     * @param string $suffix
     * @return string
     */
    public function getModel($suffix = ''): string
    {
        $parts = [
            $this->module->getNamespace(),
            $this->module->getModuleName(),
            'Model',
            $this->getNameSingular()
        ];
        return $this->stringUtil->glueClassParts($parts) . $suffix;
    }

    /**
     * @param string $suffix
     * @return string
     */
    public function getAdminController($name): string
    {
        $parts = [
            $this->module->getNamespace(),
            $this->module->getModuleName(),
            'Controller',
            'Adminhtml',
            $this->getNameSingular(),
            $name
        ];
        return $this->stringUtil->glueClassParts($parts);
    }

    /**
     * @return string
     */
    public function getResourceModel(): string
    {
        $parts = [
            $this->module->getNamespace(),
            $this->module->getModuleName(),
            'Model',
            'ResourceModel',
            $this->getNameSingular()
        ];
        return $this->stringUtil->glueClassParts($parts);
    }

    /**
     * @return string
     */
    public function getCollectionModel(): string
    {
        return $this->getResourceModel() . '\Collection';
    }

    /**
     * @return string
     */
    public function getRepoModel(): string
    {
        $parts = [
            $this->module->getNamespace(),
            $this->module->getModuleName(),
            'Model',
            $this->getNameSingular()

        ];
        return $this->stringUtil->glueClassParts($parts) . 'Repo';
    }

    /**
     * @return string
     */
    public function getListRepoModel(): string
    {
        $parts = [
            $this->module->getNamespace(),
            $this->module->getModuleName(),
            'Model',
            $this->getNameSingular()

        ];
        return $this->stringUtil->glueClassParts($parts) . 'ListRepo';
    }

    /**
     * @return string
     */
    public function getSearchModel(): string
    {
        $parts = [
            $this->module->getNamespace(),
            $this->module->getModuleName(),
            'Model',
            'Search',
            $this->getNameSingular()

        ];
        return $this->stringUtil->glueClassParts($parts);
    }

    /**
     * @return string
     */
    public function getInterface(): string
    {
        $parts = [
            $this->module->getNamespace(),
            $this->module->getModuleName(),
            'Api',
            'Data',
            $this->getNameSingular(),
        ];
        return $this->stringUtil->glueClassParts($parts) . 'Interface';
    }

    /**
     * @return string
     */
    public function getRepoInterface(): string
    {
        $parts = [
            $this->module->getNamespace(),
            $this->module->getModuleName(),
            'Api',
            $this->getNameSingular(),
        ];
        return $this->stringUtil->glueClassParts($parts) . 'RepositoryInterface';
    }

    /**
     * @return string
     */
    public function getListRepoInterface(): string
    {
        $parts = [
            $this->module->getNamespace(),
            $this->module->getModuleName(),
            'Api',
            $this->getNameSingular(),
        ];
        return $this->stringUtil->glueClassParts($parts) . 'ListRepositoryInterface';
    }

    /**
     * @return string
     */
    public function getSearchResultsInterface(): string
    {
        $parts = [
            $this->module->getNamespace(),
            $this->module->getModuleName(),
            'Api',
            'Data',
            $this->getNameSingular(),
        ];
        return $this->stringUtil->glueClassParts($parts) . 'SearchResultInterface';
    }

    /**
     * @return string
     */
    public function getPk(): string
    {
        return $this->stringUtil->snake($this->getNameSingular()) . '_id';
    }

    /**
     * @param $type
     * @param bool $tmp
     * @return string
     */
    public function getUploadFolder($type, $tmp = false): string
    {
        $parts = [
            $this->getModule()->getModuleName(),
            $this->getNameSingular(),
            $type
        ];
        if ($tmp) {
            array_splice($parts, 1, 0, 'tmp');
        }
        return implode('/', $this->stringUtil->snakeArray($parts));
    }

    /**
     * @param $type
     * @return string
     */
    public function getUploadInfoModel($type): string
    {
        $parts = [
            $this->getModule()->getModuleName(),
            $this->getNameSingular(),
            $type,
            'Info'
        ];
        return $this->stringUtil->glueClassParts($parts, '');
    }

    /**
     * @param $type
     * @return string
     */
    public function getStoreHandler($type): string
    {
        $parts = [
            $this->getModule()->getModuleName(),
            $this->getNameSingular(),
            'RelateStoreResource',
            $type,
            'Handler'
        ];
        return $this->stringUtil->glueClassParts($parts, '');
    }

    /**
     * @return string
     */
    public function getVirtualType($suffix): string
    {
        $parts = [
            $this->getModule()->getModuleName(),
            $this->getNameSingular(),
            $suffix
        ];
        return $this->stringUtil->glueClassParts($parts, '');
    }

    /**
     * @return string
     */
    public function getEventPrefix(): string
    {
        $parts = [
            $this->getModule()->getNamespace(),
            $this->getModule()->getModuleName(),
            $this->getNameSingular()
        ];
        return implode('_', $this->stringUtil->snakeArray($parts));
    }

    /**
     * @return bool
     * TODO: check this using all available processors
     */
    public function hasDataProcessor(): bool
    {
        $this->initAttributeCacheData();
        return count($this->cacheData['attribute']['data_processor_required']) > 0;
    }

    /**
     * @return bool
     */
    public function hasDateDataProcessor(): bool
    {
        return count($this->getDateAttributes()) > 0;
    }

    /**
     * @return Attribute[]
     */
    public function getDateAttributes()
    {
        return $this->getAttributesWithType('date');
    }

    /**
     * @return bool
     */
    public function hasSerializedDataProcessor(): bool
    {
        return count($this->getSerializedAttributes()) > 0;
    }

    /**
     * @return bool
     */
    public function hasMultipleAttributes(): bool
    {
        return count($this->getMultipleAttributes()) > 0;
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
    public function getMultipleAttributes(): array
    {
        $this->initAttributeCacheData();
        return $this->cacheData['attribute']['multiple'];
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

    /**
     * @return string
     */
    public function getSaveDataProcessor(): string
    {
        return ($this->hasDataProcessor())
            ? $this->getVirtualType('SaveDataProcessor')
            : $this->module->getNullSaveDataProcessor();
    }

    /**
     * @return string
     */
    public function getFormDataModifier(): string
    {
        return ($this->hasFormDataModifier())
            ? $this->getVirtualType('FormDataModifier')
            : $this->module->getNullFormDataModifier();
    }

    /**
     * @param $route
     * @return string
     */
    public function getAdminRoute($route): string
    {
        return $this->module->getAdminRoutePrefix() .
            '/' . $this->stringUtil->snake($this->getNameSingular()) .
            '/' . $route;
    }

    /**
     * @return string
     */
    public function getParentResourceModel(): string
    {
        return ($this->isStore()) ? 'StoreAwareAbstractModel' : 'AbstractModel';
    }

    /**
     * @return string
     */
    public function getParentCollectionModel(): string
    {
        return ($this->isStore()) ? 'StoreAwareAbstractCollection' : 'AbstractCollection';
    }
}
