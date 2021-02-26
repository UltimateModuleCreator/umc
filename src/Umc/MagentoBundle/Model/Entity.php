<?php

/**
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

namespace App\Umc\MagentoBundle\Model;

use App\Umc\CoreBundle\Model\Attribute\Factory as AttributeFactory;
use App\Umc\CoreBundle\Model\Module;
use App\Umc\CoreBundle\Util\StringUtil;

/**
 * @method getModule() : Module
 */
class Entity extends \App\Umc\CoreBundle\Model\Entity
{
    /**
     * @var bool
     */
    protected $productAttribute;
    /**
     * @var string
     */
    protected $productAttributeCode;
    /**
     * @var string
     */
    protected $productAttributeLabel;
    /**
     * @var string
     */
    protected $productAttributeScope;
    /**
     * @var string
     */
    protected $productAttributeType;
    /**
     * @var string
     */
    protected $productAttributeGroup;

    /**
     * @var bool
     */
    protected $categoryAttribute;
    /**
     * @var string
     */
    protected $categoryAttributeCode;
    /**
     * @var string
     */
    protected $categoryAttributeLabel;
    /**
     * @var string
     */
    protected $categoryAttributeScope;
    /**
     * @var string
     */
    protected $categoryAttributeType;
    /**
     * @var string
     */
    protected $categoryAttributeGroup;

    /**
     * @var bool
     */
    protected $customerAttribute;
    /**
     * @var string
     */
    protected $customerAttributeCode;
    /**
     * @var string
     */
    protected $customerAttributeLabel;
    /**
     * @var string
     */
    protected $customerAttributeType;
    /**
     * @var array
     */
    protected $customerAttributeForms;

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
        parent::__construct($stringUtil, $attributeFactory, $module, $data);
        $this->productAttribute = (bool)($data['product_attribute'] ?? false);
        $this->productAttributeCode = (string)($data['product_attribute_code'] ?? '');
        $this->productAttributeLabel = (string)($data['product_attribute_label'] ?? '');
        $this->productAttributeScope = (string)($data['product_attribute_scope'] ?? '');
        $this->productAttributeType = (string)($data['product_attribute_type'] ?? '');
        $this->productAttributeGroup = (string)($data['product_attribute_group'] ?? '');
        $this->categoryAttribute = (bool)($data['category_attribute'] ?? false);
        $this->categoryAttributeCode = (string)($data['category_attribute_code'] ?? '');
        $this->categoryAttributeLabel = (string)($data['category_attribute_label'] ?? '');
        $this->categoryAttributeScope = (string)($data['category_attribute_scope'] ?? '');
        $this->categoryAttributeType = (string)($data['category_attribute_type'] ?? '');
        $this->categoryAttributeGroup = (string)($data['category_attribute_group'] ?? '');
        $this->customerAttribute = (bool)($data['customer_attribute'] ?? false);
        $this->customerAttributeCode = (string)($data['customer_attribute_code'] ?? '');
        $this->customerAttributeLabel = (string)($data['customer_attribute_label'] ?? '');
        $this->customerAttributeType = (string)($data['customer_attribute_type'] ?? '');
        $this->customerAttributeForms = (array)($data['customer_attribute_forms'] ?? []);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $array = parent::toArray();
        $array['product_attribute'] = $this->productAttribute;
        $array['product_attribute_code'] = $this->productAttributeCode;
        $array['product_attribute_label'] = $this->productAttributeLabel;
        $array['product_attribute_scope'] = $this->productAttributeScope;
        $array['product_attribute_type'] = $this->productAttributeType;
        $array['product_attribute_group'] = $this->productAttributeGroup;
        $array['category_attribute'] = $this->categoryAttribute;
        $array['category_attribute_code'] = $this->categoryAttributeCode;
        $array['category_attribute_label'] = $this->categoryAttributeLabel;
        $array['category_attribute_scope'] = $this->categoryAttributeScope;
        $array['category_attribute_type'] = $this->categoryAttributeType;
        $array['category_attribute_group'] = $this->categoryAttributeGroup;
        $array['customer_attribute'] = $this->customerAttribute;
        $array['customer_attribute_code'] = $this->customerAttributeCode;
        $array['customer_attribute_label'] = $this->customerAttributeLabel;
        $array['customer_attribute_type'] = $this->customerAttributeType;
        $array['customer_attribute_forms'] = $this->customerAttributeForms;
        return $array;
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
            if ($this->hasAttributesWithType('wysiwyg')) {
                $dependencies[] = 'magento/module-widget';
            }
        }
        if ($this->hasAttributesWithFlag('upload')) {
            $dependencies[] = "magento/module-media-storage";
            $dependencies[] = "magento/module-store";
        }
        if ($this->isStore()) {
            $dependencies[] = "magento/module-store";
        }
        $hasCatalog = $this->hasAttributesWithFlag('product_attribute');
        $hasCatalog = $hasCatalog || $this->hasAttributesWithFlag('product_attribute_set');
        if ($hasCatalog) {
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
            if ($this->hasAttributesWithType('wysiwyg')) {
                $dependencies[] = 'Magento_Widget';
            }
        }
        if ($this->getAttributesWithFlag('upload')) {
            $dependencies[] = "Magento_MediaStorage";
            $dependencies[] = "Magento_Store";
        }
        if ($this->isStore()) {
            $dependencies[] = "Magento_Store";
        }
        $hasCatalog = $this->hasAttributesWithFlag('product_attribute');
        $hasCatalog = $hasCatalog || $this->hasAttributesWithFlag('product_attribute_set');
        if ($hasCatalog) {
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
        return $this->getModule()->getAclName() . '_' . $this->stringUtil->snake($this->getNameSingular());
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
     * @param string $name
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
        return $this->getModel('Repo');
    }

    /**
     * @return string
     */
    public function getListRepoModel(): string
    {
        return $this->getModel('ListRepo');
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
     * @param bool $short
     * @return string
     */
    public function getInterface($short = false): string
    {
        if (!$short) {
            $parts = [
                $this->module->getNamespace(),
                $this->module->getModuleName(),
                'Api',
                'Data',
            ];
        }
        $parts[] = $this->getNameSingular();
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
            array_splice($parts, 2, 0, 'tmp');
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
     * @param $suffix
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
     * @return string
     */
    public function getSaveDataProcessor(): string
    {
        return ($this->hasAttributesWithFlag('processor_save'))
            ? $this->getVirtualType('SaveDataProcessor')
            : $this->getModule()->getNullSaveDataProcessor();
    }

    /**
     * @return string
     */
    public function getSaveDataProcessorInlineEdit(): string
    {
        return ($this->hasAttributesWithFlag('processor_inline_edit'))
            ? $this->getVirtualType('SaveDataProcessorInlineEdit')
            : $this->getModule()->getNullSaveDataProcessor();
    }

    /**
     * @return string
     */
    public function getFormDataModifier(): string
    {
        return ($this->hasAttributesWithFlag('processor_provider'))
            ? $this->getVirtualType('FormDataModifier')
            : $this->getModule()->getNullFormDataModifier();
    }

    /**
     * @param $route
     * @return string
     */
    public function getFrontendRoute($route): string
    {
        return $this->module->getFrontKey() .
            '/' . $this->stringUtil->snake($this->getNameSingular()) .
            '/' . $route;
    }

    /**
     * @param $route
     * @return string
     */
    public function getAdminRoute($route): string
    {
        return $this->getModule()->getAdminRoutePrefix() .
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

    /**
     * @param bool $fullyQualified
     * @return string
     */
    public function getSourceModel(bool $fullyQualified = true): string
    {
        $parts = [
            $this->module->getNamespace(),
            $this->module->getModuleName(),
            'Source',
            $this->getNameSingular(),
        ];
        return $this->stringUtil->glueClassParts($fullyQualified ? $parts : [$this->getNameSingular()]);
    }

    /**
     * @return bool
     */
    public function isProductAttribute(): bool
    {
        return $this->productAttribute;
    }

    /**
     * @return string
     */
    public function getProductAttributeCode(): string
    {
        return $this->productAttributeCode ? $this->productAttributeCode : $this->getNameSingular();
    }

    /**
     * @return string
     */
    public function getProductAttributeLabel(): string
    {
        return $this->productAttributeLabel
            ? $this->productAttributeLabel
            : ($this->getProductAttributeType() === 'select' ? $this->getLabelSingular() : $this->getLabelPlural());
    }

    /**
     * @return string
     */
    public function getProductAttributeScope(): string
    {
        return $this->productAttributeScope;
    }

    /**
     * @return string
     */
    public function getProductAttributeType(): string
    {
        return $this->productAttributeType;
    }

    /**
     * @return string
     */
    public function getProductAttributeGroup(): string
    {
        return $this->productAttributeGroup;
    }

    /**
     * @return bool
     */
    public function isCategoryAttribute(): bool
    {
        return $this->categoryAttribute;
    }

    /**
     * @return string
     */
    public function getCategoryAttributeCode(): string
    {
        return $this->categoryAttributeCode ? $this->categoryAttributeCode : $this->getNameSingular();
    }

    /**
     * @return string
     */
    public function getCategoryAttributeLabel(): string
    {
        return $this->categoryAttributeLabel
            ? $this->categoryAttributeLabel
            : ($this->getCategoryAttributeType() === 'select' ? $this->getLabelSingular() : $this->getLabelPlural());
    }

    /**
     * @return string
     */
    public function getCategoryAttributeScope(): string
    {
        return $this->categoryAttributeScope;
    }

    /**
     * @return string
     */
    public function getCategoryAttributeType(): string
    {
        return $this->categoryAttributeType;
    }

    /**
     * @return string
     */
    public function getCategoryAttributeGroup(): string
    {
        return $this->categoryAttributeGroup;
    }

    /**
     * @return array|string[]
     */
    public function getFlags()
    {
        $flags = parent::getFlags();
        $this->isProductAttribute() && $flags[] = 'is_product_attribute';
        $this->isCategoryAttribute() && $flags[] = 'is_category_attribute';
        $this->isCustomerAttribute() && $flags[] = 'is_customer_attribute';
        return $flags;
    }

    /**
     * @return bool
     */
    public function isCustomerAttribute(): bool
    {
        return $this->customerAttribute;
    }

    /**
     * @return string
     */
    public function getCustomerAttributeCode(): string
    {
        return $this->customerAttributeCode ? $this->customerAttributeCode : $this->getNameSingular();
    }

    /**
     * @return string
     */
    public function getCustomerAttributeLabel(): string
    {
        return $this->customerAttributeLabel
            ? $this->customerAttributeLabel
            : ($this->getCustomerAttributeType() === 'select' ? $this->getLabelSingular() : $this->getLabelPlural());
    }

    /**
     * @return string
     */
    public function getCustomerAttributeType(): string
    {
        return $this->customerAttributeType;
    }

    /**
     * @return array
     */
    public function getCustomerAttributeForms(): array
    {
        return $this->customerAttributeForms;
    }
}
