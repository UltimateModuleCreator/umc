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

namespace App\Umc\CoreBundle\Tests\Unit\Service\Validator;

use App\Umc\CoreBundle\Model\Entity;
use App\Umc\CoreBundle\Model\Module;
use App\Umc\CoreBundle\Service\Validator\Children;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ChildrenTest extends TestCase
{
    /**
     * @var Module | MockObject
     */
    private $module;
    /**
     * @var Children
     */
    private $validator;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->module = $this->createMock(Module::class);
        $this->validator = new Children(
            'getEntities',
            'Module "%s" must have at least one entity',
            'getExtensionName'
        );
    }

    /**
     * @covers \App\Umc\CoreBundle\Service\Validator\Children::validate
     * @covers \App\Umc\CoreBundle\Service\Validator\Children::__construct
     */
    public function testValidate()
    {
        $this->module->expects($this->once())->method('getEntities')->willReturn([
            $this->createMock(Entity::class)
        ]);
        $this->module->expects($this->never())->method('getExtensionName');
        $this->assertEquals([], $this->validator->validate($this->module));
    }

    /**
     * @covers \App\Umc\CoreBundle\Service\Validator\Children::validate
     * @covers \App\Umc\CoreBundle\Service\Validator\Children::__construct
     */
    public function testValidateWithNoChildren()
    {
        $this->module->expects($this->once())->method('getEntities')->willReturn([]);
        $this->module->expects($this->once())->method('getExtensionName')->willReturn('module');
        $this->assertEquals(
            ['Module "module" must have at least one entity'],
            $this->validator->validate($this->module)
        );
    }
}
