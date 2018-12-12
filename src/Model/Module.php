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

class Module extends AbstractModel
{
    /**
     * @var array
     */
    protected $propertyNames = [
        'namespace', 'module_name', 'version', 'menu_parent',
        'sort_order', 'menu_text', 'license', 'composer_description'
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
     * Module constructor.
     * @param array $licenseFormatter
     * @param array $menuConfig
     * @param array $data
     */
    public function __construct(array $licenseFormatter, array $menuConfig, array $data = [])
    {
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
        return $this->getData('namespace') . '_' . $this->getData('module_name');
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
        return array_filter(
            $this->getEntities(),
            function (Entity $entity) {
                return $entity->getData('search') !== "0";
            }
        );
    }

    /**
     * @return array
     */
    public function getAclMenuParents()
    {
        $menuParent = $this->getData('menu_parent');
        $parents = [];
        while ($menuParent != '') {
            $parents[] = $menuParent;
            $menuParent = $this->menuConfig[$menuParent]['parent'] ?? '';
        };
        return array_reverse($parents);
    }
}
