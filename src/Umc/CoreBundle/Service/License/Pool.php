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
        array_walk(
            $processors,
            function (Processor $processor) {
                $this->addProcessor($processor);
            }
        );
    }

    /**
     * @param Processor $processor
     */
    private function addProcessor(Processor $processor)
    {
        $this->processors[$processor->getCode()] = $processor;
    }
}
