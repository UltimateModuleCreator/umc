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
use App\Util\CodingStandardsFactory;
use App\Util\YamlLoader;

class Builder
{
    /**
     * @var ArchiverFactory
     */
    private $archiverFactory;
    /**
     * @var Generator
     */
    private $generator;
    /**
     * @var WriterFactory
     */
    private $writerFactory;
    /**
     * @var CodingStandardsFactory
     */
    private $codingStandardsFactory;
    /**
     * @var YamlLoader
     */
    private $yamlLoader;
    /**
     * @var string
     */
    private $basePath;
    /**
     * @var string
     */
    private $configFilePath;

    /**
     * Builder constructor.
     * @param ArchiverFactory $archiverFactory
     * @param Generator $generator
     * @param WriterFactory $writerFactory
     * @param CodingStandardsFactory $codingStandardsFactory
     * @param string $basePath
     */
    public function __construct(
        ArchiverFactory $archiverFactory,
        Generator $generator,
        WriterFactory $writerFactory,
        CodingStandardsFactory $codingStandardsFactory,
        YamlLoader $yamlLoader,
        string $basePath,
        string $configFilePath
    ) {
        $this->archiverFactory = $archiverFactory;
        $this->generator = $generator;
        $this->writerFactory = $writerFactory;
        $this->codingStandardsFactory = $codingStandardsFactory;
        $this->yamlLoader = $yamlLoader;
        $this->basePath = $basePath;
        $this->configFilePath = $configFilePath;
    }

    /**
     * @param Module $module
     * @throws \Exception
     */
    public function buildModule(Module $module): void
    {
        $moduleName = $module->getExtensionName();
        $writer = $this->writerFactory->create($this->basePath);
        $files = $this->generator->generateModule($module);
        $writer->writeFiles($files, $moduleName);
        $csFiles = $this->codingStandardsFactory->create($this->basePath . $moduleName)->run();
        $writer->writeFiles($csFiles, $moduleName);
        $this->archiverFactory->create($this->basePath)->createZip($this->basePath . $moduleName, $moduleName);
        $configWriter = $this->writerFactory->create($this->configFilePath);
        $configWriter->writeFiles([
            $moduleName . '.yml' => $this->yamlLoader->arrayToYaml($module->toArray())
        ]);
    }
}
