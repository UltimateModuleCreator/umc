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

namespace App\Umc\CoreBundle\Service\Cs;

class Executor
{
    /**
     * @var ProcessFactory
     */
    private $processFactory;
    /**
     * @var string
     */
    private $phpcsPath;
    /**
     * @var string
     */
    private $resultFolder;

    /**
     * Executor constructor.
     * @param ProcessFactory $processFactory
     * @param string $phpcsPath
     * @param string $resultFolder
     */
    public function __construct(ProcessFactory $processFactory, string $phpcsPath, string $resultFolder = '_phpcs/')
    {
        $this->processFactory = $processFactory;
        $this->phpcsPath = $phpcsPath;
        $this->resultFolder = $resultFolder;
    }

    /**
     * @param $standards
     * @param $path
     * @return array
     */
    public function run($standards, $path)
    {
        $result = [];
        $commands = [];
        foreach ($standards as $standard) {
            $commandConfig = [
                $this->phpcsPath,
                '--standard=' . $standard,
                $path
            ];
            $process = $this->processFactory->create($commandConfig);
            $process->run();
            $output = $process->getOutput();
            $name = basename($standard);
            $result[$this->resultFolder . $name] = ($output) ? $output : "You are {$name} coding standard compliant";
        }
        return $result;
    }
}
