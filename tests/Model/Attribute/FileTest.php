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

class FileTest extends TestCase
{
    /**
     * @covers \App\Model\Attribute\File::getPropertyNames
     */
    public function testGetPropertyNames()
    {
        /** @var \Twig\Environment | MockObject $twig */
        $twig = $this->createMock(\Twig\Environment::class);
        /** @var Attribute | MockObject $attribute */
        $attribute = $this->createMock(Attribute::class);
        $file = new Attribute\File($twig, $attribute, []);
        $this->assertArrayHasKey('upload_type', $file->getPropertiesData());
    }
}
