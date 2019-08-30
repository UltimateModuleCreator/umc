<?php
/**
 *
 * UMC
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 *  that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 *
 * @copyright Marius Strajeru
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 * @author    Marius Strajeru <ultimate.module.creator@gmail.com>
 *
 */
namespace App\Tests\Model\Section;

use App\Model\Section\Loader;
use App\Util\FinderFactory;
use App\Util\YamlLoader;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class LoaderTest extends TestCase
{
    /**
     * @var YamlLoader | MockObject
     */
    private $yamlLoader;
    /**
     * @var Filesystem | MockObject
     */
    private $filesystem;
    /**
     * @var FinderFactory | MockObject
     */
    private $finderFactory;
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
        $this->filesystem = $this->createMock(Filesystem::class);
        $this->finderFactory = $this->createMock(FinderFactory::class);
        $this->loader = new Loader($this->yamlLoader, $this->filesystem, $this->finderFactory, 'base_dir');
        /** @var Finder | MockObject $finder */
        $finder = $this->createMock(Finder::class);
        $finder->method('in')->willReturnSelf();
        $finder->method('files')->willReturnSelf();
        $finder->method('depth')->willReturnSelf();
        $finder->method('name')->willReturnSelf();
        $this->finderFactory->method('create')->willReturn($finder);
        $finder->method('getIterator')->willReturn(['file1', 'file2', 'file3']);
        $this->yamlLoader->method('load')->willReturnMap(
            [
                [
                    'file1',
                    [
                        'root' => [
                            'label' => 'Root',
                            'code' => 'root'
                        ],
                        'node1' => [
                            'label' => 'Node 1',
                            'code' => 'node1',
                            'dependencies' => ['root']
                        ],
                        'node2' => [
                            'label' => 'Node 2',
                            'code' => 'node2',
                            'dependencies' => ['root']
                        ],
                    ]
                ],
                [
                    'file2',
                    [
                        'node3' => [
                            'label' => 'Node 3',
                            'code' => 'node3'
                        ],
                        'node4' => [
                            'label' => 'Node 4',
                            'code' => 'node4',
                            'dependencies' => ['node2']
                        ],
                        'node5' => [
                            'label' => 'Node 5',
                            'code' => 'node5',
                        ],
                    ]
                ],
                [
                    'file3',
                    [
                        'node6' => [
                            'label' => 'Node 6',
                            'code' => 'node4'
                        ],
                    ]
                ],
            ]
        );
    }

    /**
     * @covers \App\Model\Section\Loader::getSectionTree
     */
    public function testGetSectionTree()
    {
        $this->loader->getSectionTree();
    }
}
