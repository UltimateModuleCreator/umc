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

/**
 * @method getModule() : Module
 */
class Entity extends \App\Umc\CoreBundle\Model\Entity
{
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
}
