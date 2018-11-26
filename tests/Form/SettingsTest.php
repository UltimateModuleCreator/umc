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

use App\Form\Settings;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormBuilderInterface;

class SettingsTest extends TestCase
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
     * @covers \App\Form\Settings::buildForm
     */
    public function testBuildForm()
    {
        $full = new Settings();
        $this->builder->expects($this->exactly(3))->method('add');
        $full->buildForm($this->builder, []);
    }

    /**
     * @covers \App\Form\Settings::getBlockPrefix
     */
    public function testGetBlockPrefix()
    {
        $settings = new Settings();
        $this->assertEquals(Settings::DEFAULT_BLOCK_PREFIX, $settings->getBlockPrefix());
    }
}
