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

use App\Umc\CoreBundle\Model\Entity\Factory as EntityFactory;
use App\Service\License\ProcessorInterface;
use App\Umc\CoreBundle\Util\StringUtil;

class Module
{
    /**
     * @var Entity[]
     */
    protected $entities = [];
    /**
     * @var ProcessorInterface[]
     */
    protected $licenseFormatter;
    /**
     * @var StringUtil
     */
    protected $stringUtil;
    /**
     * @var EntityFactory
     */
    protected $entityFactory;
    /**
     * @var string
     */
    protected $namespace;
    /**
     * @var string
     */
    protected $moduleName;
    /**
     * @var string
     */
    protected $menuText;
    /**
     * @var string
     */
    protected $menuParent;
    /**
     * @var int
     */
    protected $sortOrder;
    /**
     * @var bool
     */
    protected $frontend;
    /**
     * @var string
     */
    protected $frontKey;
    /**
     * @var string
     */
    protected $license;
    /**
     * @var string
     */
    protected $configTab;
    /**
     * @var int
     */
    protected $configTabPosition;

    /**
     * @var bool[]
     */
    protected $flags = [];
    /**
     * @var array
     */
    protected $cacheData = [];
    /**
     * @var array
     */
    protected $cacheEntityData;

    /**
     * Module constructor.
     * @param StringUtil $stringUtil
     * @param EntityFactory $entityFactory
     * @param array $licenseFormatter
     * @param array $data
     */
    public function __construct(
        StringUtil $stringUtil,
        EntityFactory $entityFactory,
        //array $licenseFormatter,
        //array $menuConfig,
        array $data = []
    ) {
        $this->stringUtil = $stringUtil;
        $this->entityFactory = $entityFactory;
//        $this->licenseFormatter = $licenseFormatter;
        $this->namespace = (string)($data['namespace'] ?? '');
        $this->moduleName = (string)($data['module_name'] ?? '');
        $this->menuText = (string)($data['menu_text'] ?? '');
        $this->menuParent = (string)($data['menu_parent'] ?? '');
        $this->sortOrder = (int)($data['sort_order'] ?? 0);
        $this->license = (string)($data['license'] ?? '');
        $this->frontend = (bool)($data['frontend'] ?? false);
        $this->frontKey = (string)($data['front_key'] ?? '');
        $this->configTab = (string)($data['config_tab'] ?? '');
        $this->configTabPosition = (int)($data['config_tab_position'] ?? '');
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
        return $this->stringUtil->ucfirst($this->stringUtil->camel($this->namespace));
    }

    /**
     * @return string
     */
    public function getModuleName(): string
    {
        return $this->stringUtil->ucfirst($this->stringUtil->camel($this->moduleName));
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
            'front_key' => $this->frontKey,
            'license' => $this->license,
            'frontend' => $this->frontend,
            'config_tab' => $this->configTab,
            'config_tab_position' => $this->configTabPosition,
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
    public function getFormattedLicense(string $format): string
    {
        return ''; //TODO; move this outside the module class
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
    public function getExtensionName($separator = '_'): string
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
     * @return bool
     */
    public function isFrontend(): bool
    {
        return $this->frontend;
    }

    /**
     * loop once through the entities to check different settings
     */
    protected function initEntityCacheData()
    {
        if (isset($this->cacheData['entity'])) {
            return;
        }
//        $this->cacheData = [
//            'entity' => [
//                'frontend_view' => [],
//                'frontend_list' => [],
//                'frontend' => [],
//                'search' => [],
//                'main_menu' => [],
//                'footer_links' => [],
//                'with_upload' => [],
//                'store' => [],
//                'image' => [],
//                'file' => [],
//                'product_attribute' => [],
//                'product_attribute_set' => [],
//                'option_attribute' => [],
//            ]
//        ];
//        foreach ($this->getProcessorTypes() as $processorType) {
//            $this->cacheData['entity']['processor'][$processorType] = [];
//        }
        foreach ($this->getEntities() as $entity) {
            $this->cacheEntityData($entity);
//            if ($entity->isFrontend()) {
//                $this->cacheData['entity']['frontend'][] = $entity;
//            }
//            if ($entity->isSearch()) {
//                $this->cacheData['entity']['search'][] = $entity;
//            }
//            if ($entity->hasAttributeType('image')) {
//                $this->cacheData['entity']['image'][] = $entity;
//            }
//            if ($entity->hasAttributeType('file')) {
//                $this->cacheData['entity']['file'][] = $entity;
//            }
//            foreach ($this->getProcessorTypes() as $processorType) {
//                foreach ($entity->getAttributesWithProcessor($processorType) as $type => $attributes) {
//                    $this->cacheData['entity']['processor'][$processorType][$type] =
//                        $this->cacheData['entity']['processor'][$processorType][$type] ?? [];
//                    $this->cacheData['entity']['processor'][$processorType][$type][] = $entity;
//                }
//            }
        }
    }

    /**
     * @param Entity $entity
     */
    public function cacheEntityData(Entity $entity): void
    {
        foreach ($entity->getFlags() as $flag) {
            $this->cacheData[$flag] = $this->cacheData[$flag] ?? [];
            $this->cacheData[$flag][] = $entity;
        }
    }

    /**
     * @param $flag
     * @return Entity[]
     */
    public function getEntitiesWithFlag($flag): array
    {
        $this->initEntityCacheData();
        return $this->cacheData[$flag] ?? [];
    }

    /**
     * @param $flag
     * @return bool
     */
    public function hasEntitiesWithFlag($flag): bool
    {
        return count($this->getEntitiesWithFlag($flag)) > 0;
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
     * @return array
     */
    public function getProcessorTypes(): array
    {
        return ['save', 'provider', 'frontend', 'inlineEdit'];
    }
}
