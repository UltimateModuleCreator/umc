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

namespace App\Model\Attribute;

class Dropdown extends AbstractType implements TypeInterface
{
    /**
     * @return array
     */
    protected function getPropertyNames() : array
    {
        $names = parent::getPropertyNames();
        $names[] = 'multiple_text';
        return $names;
    }

    /**
     * @return string
     */
    public function getSourceModel() : string
    {
        $entity = $this->getAttribute()->getEntity();
        $module = $entity->getModule();
        $sourceModel = [
            $module->getNamespace(),
            $module->getModuleName(),
            'Model',
            ucfirst($entity->getNameSingular()),
            'Source',
            ucfirst($this->camelize($this->getAttribute()->getCode()))
        ];
        return implode('\\', $sourceModel);
    }

    /**
     * @param string $string
     * @return string
     */
    protected function camelize(string $string): string
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
    }
}
