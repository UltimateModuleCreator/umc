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
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CountryTest extends TestCase
{
    /**
     * @var \Twig\Environment | MockObject
     */
    private $twig;
    /**
     * @var Attribute\Country
     */
    private $country;
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
        $this->country = new Attribute\Country($this->twig, $this->attribute, ['source_model' => 'dummy']);
    }

    /**
     * @covers \App\Model\Attribute\Country::getPropertyNames
     */
    public function testGetPropertyNames()
    {
        $this->assertArrayHasKey('source_model', $this->country->getPropertiesData());
        $this->assertArrayHasKey('multiple_text', $this->country->getPropertiesData());
    }

    /**
     * @covers \App\Model\Attribute\Country::getSourceModel
     */
    public function testGetSourceModel()
    {
        $this->assertEquals('dummy', $this->country->getSourceModel());
    }
}
