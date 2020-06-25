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

class Children implements ValidatorInterface
{
    /**
     * @var string
     */
    private $getter;
    /**
     * @var string
     */
    private $errorMessage;
    /**
     * @var string
     */
    private $nameGetter;

    /**
     * Children constructor.
     * @param string $getter
     * @param string $errorMessage
     * @param string $nameGetter
     */
    public function __construct(string $getter, string $errorMessage, string $nameGetter)
    {
        $this->getter = $getter;
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
        $nameGetter = $this->nameGetter;
        return count($object->$getter()) === 0
            ? [sprintf($this->errorMessage, $object->$nameGetter())]
            : [];
    }
}
