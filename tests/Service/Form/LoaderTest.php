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

namespace App\Tests\Form;

use App\Service\Form\Loader;
use App\Service\Form\OptionProviderInterface;
use App\Service\Form\OptionProviderPool;
use App\Util\YamlLoader;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class LoaderTest extends TestCase
{
    /**
     * @var YamlLoader | MockObject
     */
    private $yamlLoader;
    /**
     * @var OptionProviderPool | MockObject
     */
    private $optionProviderPool;
    /**
     * @var Loader
     */
    private $loader;

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->yamlLoader = $this->createMock(YamlLoader::class);
        $this->optionProviderPool = $this->createMock(OptionProviderPool::class);
        $this->loader = new Loader(
            $this->yamlLoader,
            ['file'],
            $this->optionProviderPool
        );
    }

    /**
     * @covers \App\Service\Form\Loader::getForms
     * @covers \App\Service\Form\Loader::parseValue
     * @covers \App\Service\Form\Loader::__construct
     */
    public function testGetForms()
    {
        $this->yamlLoader->method('load')->willReturn([
            'dummy' => [
                'optionProvider' => 'dummy'
            ],
            'optionProvider' => 'root'
        ]);
        $this->optionProviderPool->method('getProvider')->willReturnMap([
            ['dummy', $this->getProviderMock(['dummy_options'])],
            ['root', $this->getProviderMock(['root_options'])],
        ]);
        $expected = [
            [
                'dummy' => [
                    'optionProvider' => 'dummy',
                    'options' => ['dummy_options']
                ],
                'optionProvider' => 'root',
                'options' => ['root_options']
            ]
        ];
        $this->assertEquals($expected, $this->loader->getForms());
    }

    private function getProviderMock($options)
    {
        $provider = $this->createMock(OptionProviderInterface::class);
        $provider->method('getOptions')->willReturn($options);
        return $provider;
    }
}
