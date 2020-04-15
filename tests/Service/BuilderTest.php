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

namespace App\Tests\Service;

use App\Model\Module;
use App\Service\Archiver;
use App\Service\ArchiverFactory;
use App\Service\Builder;
use App\Service\Generator;
use App\Service\Writer;
use App\Service\WriterFactory;
use App\Util\CodingStandards;
use App\Util\CodingStandardsFactory;
use App\Util\YamlLoader;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class BuilderTest extends TestCase
{
    /**
     * @covers \App\Service\Builder::buildModule
     * @covers \App\Service\Builder::__construct
     */
    public function testBuildModule()
    {
        /** @var Module | MockObject $module */
        $module = $this->createMock(Module::class);
        $module->expects($this->once())->method('getExtensionName')->willReturn('Namespace_Module');
        $module->expects($this->once())->method('toArray')->willReturn(['namespace' => 'Namespace']);
        /** @var WriterFactory | MockObject $writerFactory */
        $writerFactory = $this->createMock(WriterFactory::class);
        /** @var Writer | MockObject $writerFiles */
        $writerFiles = $this->createMock(Writer::class);
        /** @var Writer | MockObject $writerYaml */
        $writerYaml = $this->createMock(Writer::class);
        $writerFactory->expects($this->exactly(2))->method('create')
            ->willReturnOnConsecutiveCalls($writerFiles, $writerYaml);
        $writerFiles->expects($this->exactly(2))->method('writeFiles');

        $writerYaml->expects($this->once())->method('writeFiles');
        /** @var Generator | MockObject $generator */
        $generator = $this->createMock(Generator::class);
        $generator->expects($this->once())->method('generateModule')->willReturn(['file' => 'content']);
        /** @var YamlLoader | MockObject $yamlLoader */
        $yamlLoader = $this->createMock(YamlLoader::class);
        /** @var CodingStandardsFactory | MockObject $codingStandardsFactory */
        $codingStandardsFactory = $this->createMock(CodingStandardsFactory::class);
        /** @var CodingStandards | MockObject $codingStandards */
        $codingStandards = $this->createMock(CodingStandards::class);
        $codingStandardsFactory->expects($this->once())->method('create')->willReturn($codingStandards);
        $codingStandards->expects($this->once())->method('run')->willReturn(['PSR' => '0']);
        /** @var ArchiverFactory | MockObject $archiverFactory */
        $archiverFactory = $this->createMock(ArchiverFactory::class);
        /** @var Archiver | MockObject $archiver */
        $archiver = $this->createMock(Archiver::class);
        $archiverFactory->expects($this->once())->method('create')->willReturn($archiver);
        $archiver->expects($this->once())->method('createZip');
        /** @var Builder $builder */
        $builder = new Builder(
            $archiverFactory,
            $generator,
            $writerFactory,
            $codingStandardsFactory,
            $yamlLoader,
            'dummy/path',
            'config/path'
        );
        $builder->buildModule($module);
    }
}
