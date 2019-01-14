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

class AttributeSet extends Dropdown implements TypeInterface
{

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
            'Source',
            'ProductAttributeSet'
        ];
        return implode('\\', $sourceModel);
    }

    /**
     * @return array
     */
    protected function getAttributeColumnSettings(): array
    {
        $settings = parent::getAttributeColumnSettings();
        $settings['unsigned'] = 'true';
        return $settings;
    }
}
