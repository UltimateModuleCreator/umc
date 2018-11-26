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

class Yesno extends AbstractType implements TypeInterface
{
    /**
     * @return array
     */
    protected function getPropertyNames() : array
    {
        $names = parent::getPropertyNames();
        $names[] = 'source_model';
        return $names;
    }

    /**
     * @return null|string
     */
    public function getSourceModel()
    {
        return $this->getData('source_model');
    }
}
