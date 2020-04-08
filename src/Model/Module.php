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

use App\Model\Entity\Factory as EntityFactory;
use App\Service\Form\OptionProvider\FrontendMenuLink;
use App\Service\License\ProcessorInterface;
use App\Util\StringUtil;

class Module
{
    public const UMC_VENDOR = 'Umc';
    public const UMC_MODULE = 'Crud';
    /**
     * @var Entity[]
     */
    private $entities = [];
    /**
     * @var ProcessorInterface[]
     */
    private $licenseFormatter;
    /**
     * @var array
     */
    private $menuConfig;
    /**
     * @var StringUtil
     */
    private $stringUtil;
    /**
     * @var EntityFactory
     */
    private $entityFactory;
    /**
     * @var string
     */
    private $namespace;
    /**
     * @var string
     */
    private $moduleName;
    /**
     * @var string
     */
    private $menuText;
    /**
     * @var string
     */
    private $menuParent;
    /**
     * @var int
     */
    private $sortOrder;
    /**
     * @var string
     */
    private $configTab;
    /**
     * @var int
     */
    private $configTabPosition;
    /**
     * @var string
     */
    private $frontKey;
    /**
     * @var string
     */
    private $license;
    /**
     * @var bool
     */
    private $umcCrud;
    /**
     * @var bool[]
     */
    private $flags = [];
    /**
     * @var array
     */
    private $cacheData = [];

    /**
     * Module constructor.
     * @param StringUtil $stringUtil
     * @param EntityFactory $entityFactory
     * @param array $licenseFormatter
     * @param array $menuConfig
     * @param array $data
     */
    public function __construct(
        StringUtil $stringUtil,
        EntityFactory $entityFactory,
        array $licenseFormatter,
        array $menuConfig,
        array $data = []
    ) {
        $this->stringUtil = $stringUtil;
        $this->entityFactory = $entityFactory;
        $this->licenseFormatter = $licenseFormatter;
        $this->menuConfig = $menuConfig;
        $this->namespace = (string)($data['namespace'] ?? '');
        $this->moduleName = (string)($data['module_name'] ?? '');
        $this->menuText = (string)($data['menu_text'] ?? '');
        $this->menuParent = (string)($data['menu_parent'] ?? '');
        $this->sortOrder = (int)($data['sort_order'] ?? 0);
        $this->configTab = (string)($data['config_tab'] ?? '');
        $this->configTabPosition = (int)($data['config_tab_position'] ?? '');
        $this->frontKey = (string)($data['front_key'] ?? '');
        $this->license = (string)($data['license'] ?? '');
        $this->umcCrud = (bool)($data['umc_crud'] ?? false);
        foreach (($data['_entities'] ?? []) as $entity) {
            $this->entities[] = $this->entityFactory->create($this, $entity);
        }
    }

    /**
     * @return Entity[]
     */
    public function getEntities(): array
    {
        return $this->entities;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @return string
     */
    public function getModuleName(): string
    {
        return $this->moduleName;
    }

    /**
     * @return null|string
     */
    public function getMenuParent(): string
    {
        return $this->menuParent;
    }

    /**
     * @return int
     */
    public function getSortOrder(): int
    {
        return $this->sortOrder;
    }

    /**
     * @return string
     */
    public function getMenuText(): string
    {
        return $this->menuText;
    }

    /**
     * @return string
     */
    public function getLicense(): string
    {
        return $this->license;
    }

    /**
     * @return string
     */
    public function getFrontKey(): string
    {
        return ($this->frontKey)
            ? $this->frontKey
            : $this->stringUtil->snake($this->getNamespace()) . '_' . $this->stringUtil->snake($this->getModuleName());
    }

    /**
     * @return string
     */
    public function getConfigTab(): string
    {
        return $this->configTab ? $this->configTab : $this->getModuleName();
    }

    /**
     * @return int
     */
    public function getConfigTabPosition(): int
    {
        return $this->configTabPosition;
    }

    /**
     * @return bool
     */
    public function isUmcCrud(): bool
    {
        return $this->umcCrud;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'namespace' => $this->namespace,
            'module_name' => $this->moduleName,
            'menu_text' => $this->menuText,
            'menu_parent' => $this->menuParent,
            'sort_order' => $this->sortOrder,
            'config_tab' => $this->configTab,
            'config_tab_position' => $this->configTabPosition,
            'front_key' => $this->frontKey,
            'license' => $this->license,
            'umc_crud' => $this->umcCrud,
            '_entities' => array_map(
                function (Entity $entity) {
                    return $entity->toArray();
                },
                $this->entities
            )
        ];
    }

    /**
     * @param string $format
     * @return string
     * @throws \Exception
     */
    public function getFormattedLicense(string $format) : string
    {
        if (!isset($this->licenseFormatter[$format])) {
            throw new \Exception("Unsupported licenese formatter {$format}");
        }
        $formatter = $this->licenseFormatter[$format];
        if (!$formatter instanceof ProcessorInterface) {
            throw new \Exception("License formatter should implement " . ProcessorInterface::class);
        }
        return $formatter->process($this);
    }

    /**
     * @param string $separator
     * @return string
     */
    public function getExtensionName($separator = '_') : string
    {
        $parts = [$this->getNamespace(), $this->getModuleName()];
        return implode(
            $separator,
            array_map(
                function (string $text) {
                    return $this->stringUtil->ucfirst(
                        $this->stringUtil->camel($text)
                    );
                },
                $parts
            )
        );
    }

    /**
     * @return string
     */
    public function getComposerExtensionName(): string
    {
        return $this->stringUtil->hyphen($this->getNamespace()) . '/module-' .
            $this->stringUtil->hyphen($this->getModuleName());
    }

    /**
     * @return string[]
     */
    public function getModuleDependencies(): array
    {
        if (isset($this->cacheData['module']['dependencies'])) {
            return $this->cacheData['module']['dependencies'];
        }
        $dependencies = [
            "Magento_Backend",
            "Magento_Ui",
            "Magento_Config"
        ];
        if ($this->isUmcCrud()) {
            $dependencies[] = 'Umc_Crud';
        }
        $dependencies = array_reduce(
            $this->entities,
            function ($all, Entity $entity) {
                return array_merge($all, $entity->getModuleDependencies());
            },
            $dependencies
        );
        $this->cacheData['module']['dependencies'] = array_unique($dependencies);
        return $this->cacheData['module']['dependencies'];
    }

    /**
     * @return string[]
     */
    public function getComposerDependencies(): array
    {
        if (isset($this->cacheData['module']['composer_dependencies'])) {
            return $this->cacheData['module']['composer_dependencies'];
        }
        $dependencies = [
            "magento/module-backend",
            "magento/module-config",
            "magento/module-ui",
            "magento/framework"
        ];
        if ($this->isUmcCrud()) {
            $dependencies[] = 'umc/module-crud';
        }
        $dependencies = array_reduce(
            $this->entities,
            function ($all, Entity $entity) {
                return array_merge($all, $entity->getComposerDependencies());
            },
            $dependencies
        );
        $this->cacheData['module']['composer_dependencies'] = array_unique($dependencies);
        return $this->cacheData['module']['composer_dependencies'];
    }

    /**
     * @return string
     */
    public function getAclName(): string
    {
        return $this->getExtensionName() . '::' . $this->stringUtil->snake($this->getModuleName());
    }

    /**
     * @param $type
     * @return bool
     */
    public function hasAttributeType($type) : bool
    {
        if (isset($this->flags['attribute_type'][$type])) {
            return $this->flags['attribute_type'][$type];
        }
        foreach ($this->getEntities() as $entity) {
            if ($entity->hasAttributeType($type)) {
                $this->flags['attribute_type'][$type] = true;
                return $this->flags['attribute_type'][$type];
            }
        }
        $this->flags['attribute_type'][$type] = false;
        return $this->flags['attribute_type'][$type];
    }

    /**
     * @return bool
     */
    public function hasSearchableEntities() : bool
    {
        return count($this->getSearchableEntities()) > 0;
    }

    /**
     * @return Entity[]
     */
    public function getSearchableEntities() : array
    {
        $this->initEntityCacheData();
        return $this->cacheData['entity']['search'];
    }

    /**
     * @return array
     */
    public function getAclMenuParents() : array
    {
        if (isset($this->cacheData['menu_parents'])) {
            return $this->cacheData['menu_parents'];
        }
        $menuParent = $this->getMenuParent();
        $parents = [];
        while ($menuParent != '') {
            if (isset($this->menuConfig[$menuParent]['acl'])) {
                $parents = array_merge($parents, array_reverse($this->menuConfig[$menuParent]['acl']));
                break;
            }
            $parents[] = $menuParent;
            $menuParent = $this->menuConfig[$menuParent]['parent'] ?? '';
        };
        $this->cacheData['menu_parents'] = array_reverse($parents);
        return $this->cacheData['menu_parents'];
    }

    /**
     * @return bool
     */
    public function isFrontend(): bool
    {
        $this->initEntityCacheData();
        return count($this->cacheData['entity']['frontend']) > 0;
    }

    /**
     * @return array
     */
    public function getFrontendViewEntities(): array
    {
        $this->initEntityCacheData();
        return $this->cacheData['entity']['frontend_view'];
    }

    /**
     * @return array
     */
    public function getFrontendListEntities(): array
    {
        $this->initEntityCacheData();
        return $this->cacheData['entity']['frontend_list'];
    }

    /**
     * @return bool
     */
    public function isUpload(): bool
    {
        $this->initEntityCacheData();
        return count($this->cacheData['entity']['with_upload']) > 0;
    }

    public function isStore(): bool
    {
        $this->initEntityCacheData();
        return count($this->getStoreEntities()) > 0;
    }

    /**
     * @return Entity[]
     */
    public function getStoreEntities()
    {
        $this->initEntityCacheData();
        return $this->cacheData['entity']['store'];
    }

    /**
     * loop once through the entities to check different settings
     */
    private function initEntityCacheData()
    {
        if (isset($this->cacheData['entity'])) {
            return;
        }
        $this->cacheData = [
            'entity' => [
                'frontend_view' => [],
                'frontend_list' => [],
                'frontend' => [],
                'search' => [],
                'main_menu' => [],
                'footer_links' => [],
                'with_upload' => [],
                'store' => [],
                'image' => [],
                'file' => [],
                'product_attribute' => [],
                'product_attribute_set' => [],
                'option_attribute' => [],
            ]
        ];
        foreach ($this->getProcessorTypes() as $processorType) {
            $this->cacheData['entity']['processor'][$processorType] = [];
        }
        foreach ($this->getEntities() as $entity) {
            if ($entity->isFrontendView()) {
                $this->cacheData['entity']['frontend_view'][] = $entity;
            }
            if ($entity->isFrontendList()) {
                $this->cacheData['entity']['frontend_list'][] = $entity;
            }
            if ($entity->isFrontendView() || $entity->isFrontendList()) {
                $this->cacheData['entity']['frontend'][] = $entity;
            }
            if ($entity->isSearch() || $entity->isSearch()) {
                $this->cacheData['entity']['search'][] = $entity;
            }
            if ($entity->getMenuLink() === FrontendMenuLink::MAIN_MENU) {
                $this->cacheData['entity']['main_menu'][] = $entity;
            }
            if ($entity->getMenuLink() === FrontendMenuLink::FOOTER) {
                $this->cacheData['entity']['footer_links'][] = $entity;
            }
            if ($entity->isUpload()) {
                $this->cacheData['entity']['with_upload'][] = $entity;
            }
            if ($entity->isStore()) {
                $this->cacheData['entity']['store'][] = $entity;
            }
            if ($entity->hasAttributeType('image')) {
                $this->cacheData['entity']['image'][] = $entity;
            }
            if ($entity->hasAttributeType('file')) {
                $this->cacheData['entity']['file'][] = $entity;
            }
            if ($entity->isProductAttribute()) {
                $this->cacheData['entity']['product_attribute'][] = $entity;
            }
            if ($entity->isProductAttributeSet()) {
                $this->cacheData['entity']['product_attribute_set'][] = $entity;
            }
            if ($entity->hasOptionAttributes()) {
                $this->cacheData['entity']['option_attribute'][] = $entity;
            }
            foreach ($this->getProcessorTypes() as $processorType) {
                foreach ($entity->getAttributesWithProcessor($processorType) as $type => $attributes) {
                    $this->cacheData['entity']['processor'][$processorType][$type] =
                        $this->cacheData['entity']['processor'][$processorType][$type] ?? [];
                    $this->cacheData['entity']['processor'][$processorType][$type][] = $entity;
                }
            }
        }
    }

    /**
     * @param $processorType
     * @return bool
     */
    public function isProcessor($processorType): bool
    {
        $this->initEntityCacheData();
        return isset($this->cacheData['entity']['processor'][$processorType]);
    }

    /**
     * @param $processorType
     * @param $type
     * @return bool
     */
    public function isProcessorWithType($processorType, $type)
    {
        $this->initEntityCacheData();
        return isset($this->cacheData['entity']['processor'][$processorType][$type]);
    }

    /**
     * @return bool
     */
    public function isProductAttribute(): bool
    {
        $this->initEntityCacheData();
        return count($this->cacheData['entity']['product_attribute']) > 0;
    }

    /**
     * @return bool
     */
    public function isProductAttributeSet(): bool
    {
        $this->initEntityCacheData();
        return count($this->cacheData['entity']['product_attribute_set']) > 0;
    }

    /**
     * @return bool
     */
    public function isOptionAttribute(): bool
    {
        $this->initEntityCacheData();
        return count($this->cacheData['entity']['option_attribute']) > 0;
    }

    /**
     * @return bool
     */
    public function hasTopMenu() : bool
    {
        return count($this->getMainMenuEntities()) > 0;
    }

    /**
     * @return bool
     */
    public function hasFooterMenu() : bool
    {
        return count($this->getFooterLinksEntities()) > 0;
    }

    /**
     * @return Entity[]
     */
    public function getFileEntities(): array
    {
        $this->initEntityCacheData();
        return $this->cacheData['entity']['file'];
    }

    /**
     * @return bool
     */
    public function isImage(): bool
    {
        return count($this->getImageEntities()) > 0;
    }

    /**
     * @return Entity[]
     */
    public function getImageEntities(): array
    {
        $this->initEntityCacheData();
        return $this->cacheData['entity']['image'];
    }

    /**
     * @return array
     */
    public function getMainMenuEntities(): array
    {
        $this->initEntityCacheData();
        return $this->cacheData['entity']['main_menu'];
    }

    /**
     * @return array
     */
    public function getFooterLinksEntities(): array
    {
        $this->initEntityCacheData();
        return $this->cacheData['entity']['footer_links'];
    }

    /**
     * @return string
     */
    public function getMockObjectUse(): string
    {
        return "\n" . 'use PHPUnit\Framework\MockObject\MockObject;';
    }

    /**
     * @return string
     */
    public function getMockObjectClass(): string
    {
        return "MockObject";
    }

    /**
     * @return string
     */
    public function getUmcCrudNamespace(): string
    {
        return $this->isUmcCrud() ? self::UMC_VENDOR : $this->getNamespace();
    }

    /**
     * @return string
     */
    public function getUmcModuleName(): string
    {
        return $this->isUmcCrud() ? self::UMC_MODULE : $this->getModuleName();
    }

    /**
     * @return string
     */
    public function getNullSaveDataProcessor(): string
    {
        $parts = [
            $this->getUmcCrudNamespace(),
            $this->getUmcModuleName(),
            'Ui',
            'SaveDataProcessor',
            'NullProcessor'
        ];
        return $this->stringUtil->glueClassParts($parts);
    }

    /**
     * @return string
     */
    public function getNullFormDataModifier(): string
    {
        $parts = [
            $this->getUmcCrudNamespace(),
            $this->getUmcModuleName(),
            'Ui',
            'Form',
            'DataModifier',
            'NullModifier'
        ];
        return $this->stringUtil->glueClassParts($parts);
    }

    /**
     * @return string
     */
    public function getAdminRoutePrefix(): string
    {
        return str_replace('_', '', $this->stringUtil->snake($this->getModuleName()));
    }

    /**
     * @return array
     */
    public function getProcessorTypes(): array
    {
        return ['save', 'provider'];
    }
}
