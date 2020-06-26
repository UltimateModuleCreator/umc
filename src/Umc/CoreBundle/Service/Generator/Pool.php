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

namespace App\Umc\CoreBundle\Service\Generator;

class Pool
{
    /**
     * @var GeneratorInterface[]
     */
    private $generators;

    /**
     * Pool constructor.
     * @param GeneratorInterface[] $generators
     */
    public function __construct(array $generators)
    {
        foreach ($generators as $key => $generator) {
            $this->addGenerator($key, $generator);
        }
    }

    /**
     * @param string $code
     * @param GeneratorInterface $generator
     */
    private function addGenerator(string $code, GeneratorInterface $generator)
    {
        $this->generators[$code] = $generator;
    }

    /**
     * @param $code
     * @return GeneratorInterface
     */
    public function getGenerator($code): GeneratorInterface
    {
        if (isset($this->generators[$code])) {
            return $this->generators[$code];
        }
        throw new \InvalidArgumentException("Missing code generator for code {$code}");
    }
}
