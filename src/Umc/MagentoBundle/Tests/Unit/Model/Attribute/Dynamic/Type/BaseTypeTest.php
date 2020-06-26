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
 *
 */

declare(strict_types=1);

namespace App\Umc\MagentoBundle\Tests\Unit\Model\Attribute\Dynamic\Type;

use App\Umc\MagentoBundle\Model\Attribute;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

class BaseTypeTest extends TestCase
{
    /**
     * @covers \App\Umc\MagentoBundle\Model\Attribute\Dynamic\Type\BaseType::getSourceModel
     */
    public function testGetSourceModel()
    {
        $twig = $this->createMock(Environment::class);
        $dynamic = $this->createMock(Attribute\Dynamic::class);
        $dynamic->method('getOptionSourceVirtualType')->willReturn('dynamicSourceModel');
        $baseType = new Attribute\Dynamic\Type\BaseType($twig, $dynamic, ['source_model' => 'source_model']);
        $this->assertEquals('source_model', $baseType->getSourceModel());

        $baseType = new Attribute\Dynamic\Type\BaseType($twig, $dynamic, []);
        $this->assertEquals('dynamicSourceModel', $baseType->getSourceModel());
    }
}
