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

namespace App\Umc\CoreBundle\Model\Attribute\Type;

class Dropdown extends BaseType
{
    /**
     * @var bool
     */
    private $isText;

    /**
     * @return string
     */
    public function getSchemaType(): string
    {
        return $this->isTextAttribute() ? 'varchar' : parent::getSchemaType();
    }

    /**
     * @return string
     */
    public function getSchemaAttributes(): string
    {
        return $this->isTextAttribute() ? ' length="255"' : parent::getSchemaAttributes();
    }

    /**
     * @return bool
     */
    private function isTextAttribute(): bool
    {
        if ($this->isText === null) {
            $this->isText = false;
            foreach ($this->getAttribute()->getOptions() as $option) {
                if (!is_numeric($option->getValue())) {
                    $this->isText = true;
                    break;
                }
            }
        }
        return $this->isText;
    }
}
