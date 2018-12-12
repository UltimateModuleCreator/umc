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

use App\Service\Menu;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class MenuTest extends TestCase
{
    /**
     * @var RouterInterface | MockObject
     */
    private $router;
    /**
     * @var RequestStack | MockObject
     */
    private $requestStack;
    /**
     * @var Request | MockObject
     */
    private $request;

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->router = $this->createMock(RouterInterface::class);
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->request = $this->createMock(Request::class);
        $this->requestStack->method('getCurrentRequest')->willReturn($this->request);
    }

    /**
     * @covers \App\Service\Menu::__construct
     * @covers \App\Service\Menu::render
     * @covers \App\Service\Menu::getSelectedPaths
     * @covers \App\Service\Menu::renderItem
     * @covers \App\Service\Menu::hasChildren
     */
    public function testRender()
    {
        $config = [
            'item1' => [
                'active' => ['path'],
                'icon' => 'icon1',
                'label' => 'Item1',
                'children' => [
                    'item11' => [
                        'active' => ['path', 'path/info'],
                        'icon' => 'icon11',
                        'label' => 'Item11',
                    ]
                ]
            ]
        ];
        $menu = new Menu(
            $this->router,
            $this->requestStack,
            $config
        );
        $this->request->method('getPathInfo')->willReturn('path/info');
        $rendered = $menu->render();
        $this->assertContains('icon1', $rendered);
        $this->assertContains('icon11', $rendered);
        $this->assertContains('Item1', $rendered);
        $this->assertContains('Item11', $rendered);
        $this->assertContains(' treeview', $rendered);
    }
}
