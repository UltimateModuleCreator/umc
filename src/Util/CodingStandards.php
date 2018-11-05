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

class CodingStandards
{
    const RESULT_KEY_PREFIX = 'PHPCS_';
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
    private $basePath;
    /**
     * @var string
     */
    private $phpcsPath;

    /**
     * CodingStandards constructor.
     * @param ProcessFactory $processFactory
     * @param array $standards
     * @param string $basePath
     * @param string $phpcsPath
     */
    public function __construct(ProcessFactory $processFactory, array $standards, string $phpcsPath, string $basePath)
    {
        $this->processFactory = $processFactory;
        $this->standards = $standards;
        $this->phpcsPath = $phpcsPath;
        $this->basePath = $basePath;
    }

    /**
     * @param $moduleName
     * @return array
     */
    public function run() : array
    {
        $result = [];
        $commands = [];
        foreach ($this->standards as $standard) {
            $commands[basename($standard)] = [
                $this->phpcsPath,
                '--standard=' . $standard,
                $this->basePath
            ];
        }
        foreach ($commands as $standard => $command) {
            $process = $this->processFactory->create($command);
            $process->run();
            $output = $process->getOutput();
            $result[self::RESULT_KEY_PREFIX . $standard] = ($output) ? $output : $standard . " says you're OK";
        }
        return $result;
    }
}
