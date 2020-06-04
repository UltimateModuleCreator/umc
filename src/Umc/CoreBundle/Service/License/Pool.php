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

namespace App\Umc\CoreBundle\Service\License;

use App\Umc\CoreBundle\Model\Module;

class Pool
{
    /**
     * @var Processor[]
     */
    private $processors;

    /**
     * Pool constructor.
     * @param Processor[] $processors
     */
    public function __construct(iterable $processors)
    {
        foreach ($processors as $processor) {
            $this->addProcessor($processor);
        }
    }

    /**
     * @param Processor $processor
     */
    private function addProcessor(Processor $processor): void
    {
        $this->processors[$processor->getCode()] = $processor;
    }

    /**
     * @param string $code
     * @return Processor
     */
    public function getProcessor(string $code): Processor
    {
        if (!isset($this->processors[$code])) {
            throw new \InvalidArgumentException("License processor with code {$code} does not exist");
        }
        return $this->processors[$code];
    }

    /**
     * @param Module $module
     * @param $code
     * @return string
     */
    public function process(Module $module, $code): string
    {
        return $this->getProcessor($code)->process($module);
    }
}
