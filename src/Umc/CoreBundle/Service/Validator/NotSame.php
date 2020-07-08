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

class NotSame implements ValidatorInterface
{
    /**
     * @var string[]
     */
    private $getters;
    /**
     * @var string
     */
    private $errorMessage;
    /**
     * @var string
     */
    private $nameGetter;

    /**
     * NotSame constructor.
     * @param string[] $getters
     * @param string $errorMessage
     * @param string $nameGetter
     */
    public function __construct(array $getters, string $errorMessage, string $nameGetter)
    {
        $this->getters = $getters;
        $this->errorMessage = $errorMessage;
        $this->nameGetter = $nameGetter;
    }

    /**
     * @param object $object
     * @return array|string[]
     */
    public function validate($object): array
    {
        $values = [];
        $nameGetter = $this->nameGetter;
        foreach ($this->getters as $getter) {
            $value = $object->$getter();
            if (in_array($value, $values)) {
                return [sprintf($this->errorMessage, $object->$nameGetter())];
            }
            $values[] = $value;
        }
        return [];
    }
}
