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
namespace App\Tests\Controller;

use App\Controller\Edit;
use App\Util\YamlLoader;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class EditTest extends TestCase
{
    /**
     * @var \Twig_Environment | MockObject
     */
    private $twig;
    /**
     * @var Router | MockObject
     */
    private $router;
    /**
     * @var FormFactoryInterface | MockObject
     */
    private $formFactory;
    /**
     * @var RequestStack | MockObject
     */
    private $requestStack;
    /**
     * @var YamlLoader | MockObject
     */
    private $yamlLoader;
    /**
     * @var array
     */
    private $attributeConfig;
    /**
     * @var Request | MockObject
     */
    private $request;
    /**
     * @var Edit
     */
    private $edit;

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->twig = $this->createMock(\Twig_Environment::class);
        $this->twig->expects($this->once())->method('render')->with($this->equalTo('template'));
        $this->router = $this->createMock(Router::class);
        $this->router->expects($this->once())->method('generate')->willReturn('route');
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->request = $this->createMock(Request::class);
        $this->requestStack->expects($this->once())->method('getCurrentRequest')->willReturn($this->request);
        $this->yamlLoader = $this->createMock(YamlLoader::class);
        $this->attributeConfig = [
            'can_be_name' => true,
            'can_show_in_grid' => false,
            'can_have_options' => true,
            'can_be_required' => false
        ];
        $this->edit = new Edit(
            'template',
            $this->requestStack,
            $this->yamlLoader,
            'basePath',
            $this->attributeConfig
        );
        $this->formFactory = $this->createMock(FormFactoryInterface::class);
        $this->formFactory->expects($this->once())->method('create')
            ->willReturn($this->createMock(FormInterface::class));
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
        $this->edit->setContainer($container);
    }

    /**
     * @covers \App\Controller\Edit::run
     * @covers \App\Controller\Edit::getAttributeConfig
     * @covers \App\Controller\Edit::__construct
     */
    public function testRunNewMode()
    {
        $this->request->method('get')->willReturn(null);
        $this->yamlLoader->expects($this->never())->method('load');
        $this->edit->run();
    }

    /**
     * @covers \App\Controller\Edit::run
     * @covers \App\Controller\Edit::getAttributeConfig
     * @covers \App\Controller\Edit::__construct
     */
    public function testEditValidData()
    {
        $this->request->method('get')->willReturn('module');
        $this->yamlLoader->expects($this->once())->method('load')->willReturn([
            'namespace' => 'Namespace',
            'module_name' => 'ModuleName'
        ]);
        $this->edit->run();
    }

    /**
     * @covers \App\Controller\Edit::run
     * @covers \App\Controller\Edit::getAttributeConfig
     * @covers \App\Controller\Edit::__construct
     */
    public function testEditNotValidData()
    {
        $this->request->method('get')->willReturn('module');
        $this->yamlLoader->expects($this->once())->method('load')->willThrowException(new \Exception());
        $this->edit->run();
    }
}
