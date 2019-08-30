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
namespace App\Tests\Attribute;

use App\Model\Attribute;
use App\Model\Attribute\Yesno;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class YesnoTest extends TestCase
{
    /**
     * @var \Twig\Environment | MockObject
     */
    private $twig;
    /**
     * @var Attribute | MockObject
     */
    private $attribute;

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->twig = $this->createMock(\Twig\Environment::class);
        $this->attribute = $this->createMock(Attribute::class);
    }

    /**
     * @covers \App\Model\Attribute\Yesno::getPropertyNames
     */
    public function testGetPropertyNames()
    {
        $yesNo = new Yesno($this->twig, $this->attribute, []);
        $this->assertArrayHasKey('source_model', $yesNo->getPropertiesData());
    }

    /**
     * @covers \App\Model\Attribute\Yesno::getSourceModel
     */
    public function testGetSourceModel()
    {
        /** @var Attribute | MockObject $attribute */
        $expected = 'dummySourceModel';
        $yesNo = new Yesno($this->twig, $this->attribute, ['source_model' => $expected]);
        $this->assertEquals($expected, $yesNo->getSourceModel());
    }
}
