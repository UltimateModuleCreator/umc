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
namespace App\Tests\Twig;

use App\Form\Full;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormBuilderInterface;

class FullTest extends TestCase
{
    /**
     * @var FormBuilderInterface | MockObject
     */
    private $builder;

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->builder = $this->createMock(FormBuilderInterface::class);
    }

    /**
     * @covers \App\Form\Full::buildForm
     */
    public function testBuildForm()
    {
        $full = new Full();
        $this->builder->expects($this->exactly(5))->method('create')->willReturn($this->builder);
        $this->builder->expects($this->exactly(6))->method('add');
        $full->buildForm($this->builder, []);
    }

    /**
     * @covers \App\Form\Full::getBlockPrefix
     */
    public function testGetBlockPrefix()
    {
        $full = new Full();
        $this->assertEquals(Full::DEFAULT_BLOCK_PREFIX, $full->getBlockPrefix());
    }
}
