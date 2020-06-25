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

class UniqueChildren implements ValidatorInterface
{
    /**
     * @var string
     */
    private $getter;
    /**
     * @var string
     */
    private $childUniqueGetter;
    /**
     * @var string
     */
    private $errorMessage;
    /**
     * @var string
     */
    private $nameGetter;

    /**
     * UniqueChildren constructor.
     * @param string $getter
     * @param string $childUniqueGetter
     * @param string $errorMessage
     * @param string $nameGetter
     */
    public function __construct(string $getter, string $childUniqueGetter, string $errorMessage, string $nameGetter)
    {
        $this->getter = $getter;
        $this->childUniqueGetter = $childUniqueGetter;
        $this->errorMessage = $errorMessage;
        $this->nameGetter = $nameGetter;
    }

    /**
     * @param object $object
     * @return array
     */
    public function validate($object): array
    {
        $getter = $this->getter;
        $uniqueGetter = $this->childUniqueGetter;
        $nameGetter = $this->nameGetter;
        $uniqueValues = [];
        foreach ($object->$getter() as $child) {
            $unique = $child->$uniqueGetter();
            $uniqueValues[$unique] = $uniqueValues[$unique] ?? [];
            $uniqueValues[$unique][] = $child;
        }
        $duplicates = array_filter(
            $uniqueValues,
            function ($value) {
                return count($value) > 1;
            }
        );
        return array_map(
            function ($item) use ($nameGetter, $object) {
                return sprintf($this->errorMessage, $object->$nameGetter(), $item);
            },
            array_keys($duplicates)
        );
    }
}
