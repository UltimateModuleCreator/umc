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

use App\Model\Entity;
use App\Model\EntityFactory;
use App\Util\Sorter;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class EntityFactoryTest extends TestCase
{
    /**
     * @covers \App\Model\EntityFactory::create
     * @covers \App\Model\EntityFactory::__construct
     */
    public function testCreate()
    {
        /** @var Sorter | MockObject $sorter */
        $sorter = $this->createMock(Sorter::class);
        $factory = new EntityFactory($sorter);
        $entity1 = $factory->create(['name_singular' => 'entity']);
        $entity2 = $factory->create();
        $this->assertNotSame($entity1, $entity2);
        $this->assertInstanceOf(Entity::class, $entity1);
        $this->assertInstanceOf(Entity::class, $entity2);
        $this->assertEquals('entity', $entity1->getData('name_singular'));
    }
}
