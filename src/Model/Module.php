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

use App\Service\License\ProcessorInterface;
use App\Util\StringUtil;

class Module extends AbstractModel
{
    const NAMESPACE = 'namespace';
    const MODULE_NAME = 'module_name';
    const VERSION  = 'version';
    const MENU_PARENT = 'menu_parent';
    const SORT_ORDER = 'sort_order';
    const MENU_TEXT = 'menu_text';
    const LICENSE = 'license';
    const FRONT_KEY = 'front_key';
    const CONFIG_TAB = 'config_tab';
    const CONFIG_TAB_POSITION = 'config_tab_position';
    const MAGENTO_VERSION = 'magento_version';
    const MAGENTO_VERSION_2_2 = '2.2';
    const MAGENTO_VERSION_2_3 = '2.3';
    const DEFAULT_MAGENTO_VERSION = self::MAGENTO_VERSION_2_2;
    /**
     * @var array
     */
    protected $propertyNames = [
        self::NAMESPACE, self::MODULE_NAME, self::VERSION, self::MENU_PARENT,
        self::SORT_ORDER, self::MENU_TEXT, self::LICENSE,
        self::FRONT_KEY, self::CONFIG_TAB, self::CONFIG_TAB_POSITION, self::MAGENTO_VERSION
    ];
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
     * Module constructor.
     * @param array $licenseFormatter
     * @param array $menuConfig
     * @param array $data
     */
    public function __construct(
        StringUtil $stringUtil,
        array $licenseFormatter,
        array $menuConfig,
        array $data = []
    ) {
        $this->stringUtil = $stringUtil;
        $this->licenseFormatter = $licenseFormatter;
        $this->menuConfig = $menuConfig;
        parent::__construct($data);
    }

    /**
     * @return Entity[]
     */
    public function getEntities() : array
    {
        return $this->entities;
    }

    /**
     * @param Entity $entity
     */
    public function addEntity(Entity $entity) : void
    {
        $entity->setModule($this);
        $this->entities[] = $entity;
    }

    /**
     * @return null|string
     */
    public function getNamespace() : ?string
    {
        return $this->getData(self::NAMESPACE);
    }

    /**
     * @return null|string
     */
    public function getModuleName() : ?string
    {
        return $this->getData(self::MODULE_NAME);
    }

    /**
     * @return null|string
     */
    public function getVersion() : ?string
    {
        return $this->getData(self::VERSION);
    }

    /**
     * @return null|string
     */
    public function getMenuParent() : ?string
    {
        return $this->getData(self::MENU_PARENT);
    }

    /**
     * @return int
     */
    public function getSortOrder() : int
    {
        return (int)$this->getData(self::SORT_ORDER);
    }

    /**
     * @return null|string
     */
    public function getMenuText() : ?string
    {
        return $this->getData(self::MENU_TEXT);
    }

    /**
     * @return null|string
     */
    public function getLicense() : ?string
    {
        return $this->getData(self::LICENSE);
    }

    /**
     * @return string
     */
    public function getFrontKey() : string
    {
        $key = $this->getData(self::FRONT_KEY);
        return ($key)
            ? $key
            : $this->stringUtil->snake($this->getNamespace()) . '_' . $this->stringUtil->snake($this->getModuleName());
    }

    /**
     * @return null|string
     */
    public function getConfigTab() : ?string
    {
        $configTab = $this->getData(self::CONFIG_TAB);
        return $configTab ? $configTab : $this->getModuleName();
    }

    /**
     * @return int
     */
    public function getConfigTabPosition() : int
    {
        return (int)$this->getData(self::CONFIG_TAB_POSITION);
    }

    /**
     * @return array
     */
    public function getAdditionalToArray() : array
    {
        $result = [];
        $result['_entities'] = array_map(
            function (Entity $item) {
                return $item->toArray();
            },
            $this->getEntities()
        );
        return $result;
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
        foreach ($this->getEntities() as $entity) {
            if ($entity->hasAttributeType($type)) {
                return true;
            }
        }
        return false;
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
        return $this->getEntitiesWithProperty('search');
    }

    /**
     * @return array
     */
    public function getAclMenuParents() : array
    {
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
        return array_reverse($parents);
    }

    /**
     * @param $property
     * @return array
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
    public function hasFrontend() : bool
    {
        return !!count($this->getEntitiesWithProperty(Entity::FRONTEND_VIEW))
            || !!count($this->getEntitiesWithProperty(Entity::FRONTEND_LIST));
    }

    /**
     * @return string
     */
    public function getMagentoVersion() : string
    {
        $version = $this->getData(self::MAGENTO_VERSION);
        return in_array($version, [self::MAGENTO_VERSION_2_2, self::MAGENTO_VERSION_2_3])
            ? $version
            : self::DEFAULT_MAGENTO_VERSION;
    }

    /**
     * @return bool
     */
    public function hasTopMenu() : bool
    {
        return (bool)count($this->getMenuEntities(Entity::MENU_LINK_MAIN_MENU));
    }

    /**
     * @return bool
     */
    public function hasFooterMenu() : bool
    {
        return (bool)count($this->getMenuEntities(Entity::MENU_LINK_FOOTER));
    }

    /**
     * @param $menuId
     * @return array
     */
    public function getMenuEntities($menuId) : array
    {
        return array_values(
            array_filter(
                $this->getEntities(),
                function (Entity $entity) use ($menuId) {
                    return $entity->getMenuLink() === $menuId;
                }
            )
        );
    }
}
