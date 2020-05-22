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

class Entity extends \App\Umc\CoreBundle\Model\Entity
{
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
     * @var \App\Umc\MagentoBundle\Model\Module
     */
    protected $module;

    /**
     * Entity constructor.
     * @param StringUtil $stringUtil
     * @param AttributeFactory $attributeFactory
     * @param Module | \App\Umc\MagentoBundle\Model\Module $module
     * @param array $data
     */
    public function __construct(
        StringUtil $stringUtil,
        AttributeFactory $attributeFactory,
        Module $module,
        array $data = []
    ) {
        parent::__construct($stringUtil, $attributeFactory, $module, $data);
        $this->store = (bool)($data['store'] ?? false);
        $this->topMenu = (bool)($data['top_menu'] ?? false);
        $this->footerLinks = (bool)($data['footer_links'] ?? false);
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
        if ($this->isProductAttribute() || $this->isProductAttributeSet()) {
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
        if ($this->isProductAttributeSet() || $this->isProductAttribute()) {
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
     * @return array
     */
    public function toArray(): array
    {
        $result = parent::toArray();
        $result['store'] = $this->store;
        $result['top_menu'] = $this->topMenu;
        $result['footer_links'] = $this->footerLinks;
        return $result;
    }

    /**
     * @return string
     */
    public function getSaveDataProcessor(): string
    {
        return (count($this->getAttributesWithProcessor('save')) > 0)
            ? $this->getVirtualType('SaveDataProcessor')
            : $this->module->getNullSaveDataProcessor();
    }

    /**
     * @return string
     */
    public function getSaveDataProcessorInlineEdit(): string
    {
        return (count($this->getAttributesWithProcessor('inlineEdit')) > 0)
            ? $this->getVirtualType('SaveDataProcessorInlineEdit')
            : $this->module->getNullSaveDataProcessor();
    }

    /**
     * @return string
     */
    public function getFormDataModifier(): string
    {
        return (count($this->getAttributesWithProcessor('provider')) > 0)
            ? $this->getVirtualType('FormDataModifier')
            : $this->module->getNullFormDataModifier();
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
