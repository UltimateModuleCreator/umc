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

use App\Umc\CoreBundle\Model\Attribute;
use App\Umc\CoreBundle\Model\Entity;
use App\Umc\CoreBundle\Model\Module;

class Pool
{
    /**
     * @var ValidatorInterface[]
     */
    private $validators;

    /**
     * Pool constructor.
     * @param array $validators
     */
    public function __construct(array $validators)
    {
        foreach ($validators as $validator) {
            if (!($validator instanceof ValidatorInterface)) {
                throw new \InvalidArgumentException("Validator must implement " . ValidatorInterface::class);
            }
        }
        $this->validators = $validators;
    }

    /**
     * @param Module $module
     * @return array
     */
    public function validate(Module $module): array
    {
        /** @var ValidatorInterface $moduleValidator */
        $moduleValidator = $this->validators['module'] ?? '';
        $errors = $moduleValidator ? $moduleValidator->validate($module) : [];
        return array_reduce(
            $module->getEntities(),
            function ($err, Entity $entity) {
                return array_merge($err, $this->validateEntity($entity));
            },
            $errors
        );
    }

    /**
     * @param Entity $entity
     * @return array
     */
    private function validateEntity(Entity $entity): array
    {
        /** @var ValidatorInterface $entityValidator */
        $entityValidator = $this->validators['entity'] ?? '';
        $errors = $entityValidator ? $entityValidator->validate($entity) : [];
        return array_reduce(
            $entity->getAttributes(),
            function ($err, Attribute $attribute) {
                return array_merge($err, $this->validateAttribute($attribute));
            },
            $errors
        );
    }

    /**
     * @param Attribute $attribute
     * @return array
     */
    private function validateAttribute(Attribute $attribute): array
    {
        /** @var ValidatorInterface $validator */
        $validator = $this->validators['attribute'] ?? '';
        $errors = $validator ? $validator->validate($attribute) : [];
        return array_reduce(
            $attribute->getDynamic(),
            function ($err, Attribute\Dynamic $dynamic) {
                return array_merge($err, $this->validateDynamic($dynamic));
            },
            $errors
        );
    }

    /**
     * @param Attribute\Dynamic $dynamic
     * @return array
     */
    private function validateDynamic(Attribute\Dynamic $dynamic): array
    {
        /** @var ValidatorInterface $validator */
        $validator = $this->validators['dynamic'] ?? '';
        return $validator ? $validator->validate($dynamic) : [];
    }
}
