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
     * @var bool[]
     */
    protected $flags = [];
    /**
     * @var array
     */
    protected $cacheData = [];

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
     * @param $type
     * @return bool
     */
    public function hasAttributeType($type): bool
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
    public function hasSearchableEntities(): bool
    {
        return count($this->getSearchableEntities()) > 0;
    }

    /**
     * @return Entity[]
     */
    public function getSearchableEntities(): array
    {
        $this->initEntityCacheData();
        return $this->cacheData['entity']['search'];
    }

    /**
     * @return bool
     */
    public function isFrontend(): bool
    {
        return $this->frontend;
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
    public function getFrontendEntities(): array
    {
        $this->initEntityCacheData();
        return $this->cacheData['entity']['frontend'];
    }

    /**
     * @return array
     * @deprecated
     */
    public function getFrontendListEntities(): array
    {
        return $this->getFrontendEntities();
    }

    /**
     * @return bool
     */
    public function isUpload(): bool
    {
        $this->initEntityCacheData();
        return count($this->cacheData['entity']['with_upload']) > 0;
    }

    /**
     * loop once through the entities to check different settings
     */
    protected function initEntityCacheData()
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
            if ($entity->isFrontend()) {
                $this->cacheData['entity']['frontend'][] = $entity;
            }
            if ($entity->isSearch()) {
                $this->cacheData['entity']['search'][] = $entity;
            }
//            if ($entity->getMenuLink() === FrontendMenuLink::MAIN_MENU) {
//                $this->cacheData['entity']['main_menu'][] = $entity;
//            }
//            if ($entity->getMenuLink() === FrontendMenuLink::FOOTER) {
//                $this->cacheData['entity']['footer_links'][] = $entity;
//            }
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
    public function isOptionAttribute(): bool
    {
        $this->initEntityCacheData();
        return count($this->cacheData['entity']['option_attribute']) > 0;
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
