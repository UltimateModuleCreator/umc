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

namespace App\Umc\CoreBundle\Service\Validator;

use App\Umc\CoreBundle\Model\Entity;

class EntityName implements ValidatorInterface
{
    /**
     * @param object $object
     * @return array
     */
    public function validate($object): array
    {
        $errors = [];
        if (!$object instanceof Entity) {
            throw new \InvalidArgumentException("Entity Name Validator can be used for Entities only");
        }
        if ($object->getNameAttribute() === null) {
            $errors[] = "Entity {$object->getLabelSingular()} must have an attribute that behaves as name";
        }
        return $errors;
    }
}
