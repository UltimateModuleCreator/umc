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
use App\Service\License\ProcessorInterface;
use App\Util\StringUtil;

class Module
{

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
        $this->sortOrder = (int)($data['namespace'] ?? 0);
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
                $this->getEntities()
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
     * @return string
     */
    public function getExtensionName() : string
    {
        return $this->getNamespace() . '_' . $this->getModuleName();
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
     * @param $property
     * @return array
     * @deprecated
     */
    public function getEntitiesWithProperty($property) : array
    {
        return array_values(
            array_filter(
                $this->getEntities(),
                function (Entity $entity) use ($property) {
                    return $entity->getData($property) !== "0" && $entity->getData($property) !== null;
                }
            )
        );
    }

    /**
     * @return bool
     */
    public function hasFrontend(): bool
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
                'footer_links' => []
            ]
        ];
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
        }
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
}
