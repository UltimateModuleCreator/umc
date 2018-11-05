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
namespace App\Tests\Model;

use App\Model\AbstractModel;
use PHPUnit\Framework\TestCase;

class AbstractModelTest extends TestCase
{
    /**
     * @covers \App\Model\AbstractModel::__construct
     * @covers \App\Model\AbstractModel::setData
     */
    public function testSetData()
    {
        $model = new AbstractModel();
        $this->assertInstanceOf(AbstractModel::class, $model->setData('dummy', 'dummy'));
    }

    /**
     * @covers \App\Model\AbstractModel::__construct
     * @covers \App\Model\AbstractModel::setData
     */
    public function testGetData()
    {
        $model = new AbstractModel([
            'dummy' => 'dummy'
        ]);
        $this->assertEquals('dummy', $model->getData('dummy'));
        $this->assertEquals('default', $model->getData('missing', 'default'));
        $this->assertNull($model->getData('missing'));
    }

    /**
     * @covers \App\Model\AbstractModel::toArray
     * @covers \App\Model\AbstractModel::getAdditionalToArray
     * @covers \App\Model\AbstractModel::getPropertyNames
     */
    public function testToArray()
    {
        $model = new AbstractModel([
            'dummy' => 'dummy'
        ]);
        $this->assertEmpty($model->toArray());
    }
}
