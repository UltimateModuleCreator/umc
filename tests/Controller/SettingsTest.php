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

namespace App\Tests\Controller;

use App\Controller\Settings;
use App\Service\Form\Loader;
use App\Service\ModuleList;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\Router;

class SettingsTest extends TestCase
{
    /**
     * @var \Twig_Environment | MockObject
     */
    private $twig;
    /**
     * @var string
     */
    private $template = 'template';
    /**
     * @var \App\Model\Settings | MockObject
     */
    private $settingsModel;
    /**
     * @var Router
     */
    private $router;
    /**
     * @var Loader | MockObject
     */
    private $formLoader;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->twig = $this->createMock(\Twig_Environment::class);
        $this->settingsModel = $this->createMock(\App\Model\Settings::class);
        $this->router = $this->createMock(Router::class);
        $this->router->expects($this->once())->method('generate')->willReturn('route');
        $this->formLoader = $this->createMock(Loader::class);
    }

    /**
     * @covers \App\Controller\Settings::run()
     * @covers \App\Controller\Settings::__construct()
     */
    public function testRun()
    {
        $this->twig->expects($this->once())->method('render')->with($this->equalTo($this->template));
        $this->settingsModel->expects($this->once())->method('getSettings')->willReturn([]);
        /** @var ModuleList | MockObject $moduleList */
        $settings = new Settings($this->twig, $this->template, $this->settingsModel, $this->formLoader);
        /** @var ContainerInterface | MockObject $container */
        $container = $this->createMock(ContainerInterface::class);
        $container->method('has')->willReturnMap([
            ['twig', true],
            ['form.factory', true],
            ['router', true]
        ]);
        $container->method('get')->willReturnMap([
            ['twig', $this->twig],
            ['form.factory', $this->formFactory],
            ['router', $this->router]
        ]);
        $settings->setContainer($container);
        $settings->run();
    }
}
