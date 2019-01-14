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

namespace App\Tests\Model;

use App\Model\Attribute;
use App\Model\AttributeFactory;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class AttributeFactoryTest extends TestCase
{
    /**
     * @covers \App\Model\AttributeFactory::create
     * @covers \App\Model\AttributeFactory::__construct
     */
    public function testCreate()
    {
        /** @var Attribute\TypeFactory  | MockObject $typeFactory */
        $typeFactory = $this->createMock(Attribute\TypeFactory::class);
        $factory = new AttributeFactory($typeFactory);
        $attribute1 = $factory->create(['code' => 'code']);
        $attribute2 = $factory->create();
        $this->assertNotSame($attribute1, $attribute2);
        $this->assertInstanceOf(Attribute::class, $attribute1);
        $this->assertInstanceOf(Attribute::class, $attribute2);
        $this->assertEquals('code', $attribute1->getCode());
    }
}
