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

use App\Umc\CoreBundle\Service\License\Pool;
use App\Umc\MagentoBundle\Model\Entity;
use App\Umc\CoreBundle\Model\Entity\Factory as EntityFactory;
use App\Umc\CoreBundle\Util\StringUtil;

class Module extends \App\Umc\CoreBundle\Model\Module
{
    public const UMC_VENDOR = 'Umc';
    public const UMC_MODULE = 'Crud';
    /**
     * @var array
     */
    private $menuConfig;
    /**
     * @var bool
     */
    protected $umcCrud;

    /**
     * Module constructor.
     * @param StringUtil $stringUtil
     * @param EntityFactory $entityFactory
     * @param array $menuConfig
     * @param array $data
     */
    public function __construct(
        StringUtil $stringUtil,
        EntityFactory $entityFactory,
        array $menuConfig,
        array $data = []
    ) {
        parent::__construct($stringUtil, $entityFactory, $data);
        $this->umcCrud = (bool)($data['umc_crud'] ?? false);
        $this->menuConfig = $menuConfig;
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
        $result = parent::toArray();
        $result['umc_crud'] = $this->umcCrud;
        return $result;
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
    public function getComposerExtensionName(): string
    {
        return $this->stringUtil->hyphen($this->getNamespace()) . '/module-' .
            $this->stringUtil->hyphen($this->getModuleName());
    }

    /**
     * @return string
     */
    public function getAclName(): string
    {
        return $this->getExtensionName() . '::' . $this->stringUtil->snake($this->getModuleName());
    }

    /**
     * @return array
     */
    public function getAclMenuParents(): array
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
}
