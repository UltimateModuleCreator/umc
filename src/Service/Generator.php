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
 */

declare(strict_types=1);

namespace App\Service;

use App\Model\Module;
use App\Service\Generator\GeneratorInterface;
use App\Service\Source\Reader;

class Generator
{
    /**
     * @var Reader
     */
    private $reader;
    /**
     * @var array
     */
    private $contentGenerators;

    /**
     * Generator constructor.
     * @param Reader $reader
     * @param array $contentGenerators
     * @param Writer $writer
     */
    public function __construct(Reader $reader, array $contentGenerators)
    {
        $this->reader = $reader;
        $this->contentGenerators = $contentGenerators;
    }

    /**
     * @param Module $module
     * @return string
     * @throws \Exception
     */
    public function generateModule(Module $module): array
    {
        $files = [];
        foreach ($this->reader->getFiles() as $file) {
            $scope = $file['scope'] ?? 'module';
            $contentGenerator = $this->getContentGenerator($scope);
            $files = array_merge($files, $contentGenerator->generateContent($module, $file));
        }
        return $files;
    }

    /**
     * @param string $scope
     * @return GeneratorInterface
     * @throws \Exception
     */
    private function getContentGenerator(string $scope): GeneratorInterface
    {
        if (!isset($this->contentGenerators[$scope])) {
            throw new \Exception("Content generator not found for scope {$scope}");
        }
        if (!($this->contentGenerators[$scope] instanceof GeneratorInterface)) {
            throw new \Exception("Content generator for scope {$scope} must implement " . GeneratorInterface::class);
        }
        return $this->contentGenerators[$scope];
    }
}
