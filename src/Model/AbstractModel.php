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
 */
declare(strict_types=1);

namespace App\Model;

class AbstractModel
{
    /**
     * @var array
     */
    protected $propertyNames = [];

    /**
     * @var array
     */
    protected $data;
    /**
     * @var array
     */
    protected $methodsCache = [];

    /**
     * AbstractModel constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * @param string $key
     * @param null|string $default
     * @return null|string
     */
    public function getData(string $key, ?string $default = null) : ?string
    {
        return isset($this->data[$key]) ? (string)$this->data[$key] : $default;
    }

    /**
     * @return array
     */
    public function getRawData() : array
    {
        return $this->data;
    }

    /**
     * @param string $key
     * @param $value
     * @return $this
     */
    public function setData(string $key, $value) : AbstractModel
    {
        $this->data[$key] = $value;
        return $this;
    }

    /**
     * @return array
     */
    public function getPropertiesData() : array
    {
        $result = [];
        foreach ($this->getPropertyNames() as $name) {
            $result[$name] = $this->getData($name);
        }
        return $result;
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        $result = $this->getPropertiesData();
        $additional = $this->getAdditionalToArray();
        if ($additional) {
            $result = array_merge($result, $additional);
        }
        return $result;
    }

    /**
     * @return array
     */
    protected function getAdditionalToArray() : array
    {
        return [];
    }

    /**
     * @return array
     */
    protected function getPropertyNames() : array
    {
        return $this->propertyNames;
    }
}
