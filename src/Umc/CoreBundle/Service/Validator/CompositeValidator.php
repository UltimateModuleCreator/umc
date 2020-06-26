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

class CompositeValidator implements ValidatorInterface
{
    /**
     * @var ValidatorInterface[]
     */
    private $validators = [];

    /**
     * CompositeValidator constructor.
     * @param ValidatorInterface[] $validators
     */
    public function __construct(iterable $validators)
    {
        foreach ($validators as $validator) {
            $this->addValidator($validator);
        }
    }

    /**
     * @param ValidatorInterface $validator
     */
    private function addValidator(ValidatorInterface $validator)
    {
        $this->validators[] = $validator;
    }

    /**
     * @param object $object
     * @return array
     */
    public function validate($object): array
    {
        return array_reduce(
            $this->validators,
            function ($errors, ValidatorInterface $validator) use ($object) {
                return array_merge($errors, $validator->validate($object));
            },
            []
        );
    }
}
