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

namespace App\Util;

class CodingStandardsFactory
{
    /**
     * @var ProcessFactory
     */
    private $processFactory;
    /**
     * @var array
     */
    private $standards;
    /**
     * @var string
     */
    private $phpcsPath;

    /**
     * CodingStandardsFactory constructor.
     * @param ProcessFactory $processFactory
     * @param array $standards
     * @param string $phpcsPath
     */
    public function __construct(ProcessFactory $processFactory, array $standards, string $phpcsPath)
    {
        $this->processFactory = $processFactory;
        $this->standards = $standards;
        $this->phpcsPath = $phpcsPath;
    }

    /**
     * @param string $basePath
     * @return CodingStandards
     */
    public function create(string $basePath): CodingStandards
    {
        return new CodingStandards($this->processFactory, $this->standards, $this->phpcsPath, $basePath);
    }
}
