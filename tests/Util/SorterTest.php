<?php
namespace App\Tests\Util;

use App\Model\AbstractModel;
use App\Util\Sorter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SorterTest extends TestCase
{
    /**
     * @covers \App\Util\Sorter::sort
     */
    public function testSortAttributes()
    {
        $sorter = new Sorter();
        $model1 = $this->getModelMock("2");
        $model2 = $this->getModelMock("1");
        $model3 = $this->getModelMock("");
        $model4 = $this->getModelMock("2");
        $model5 = $this->getModelMock("");
        $models = [$model1, $model2, $model3, $model4, $model5];
        $sorter->sort($models);
        $this->assertEquals($model2, $models[0]);
        $this->assertEquals($model1, $models[1]);
        $this->assertEquals($model4, $models[2]);
        $this->assertEquals($model3, $models[3]);
        $this->assertEquals($model5, $models[4]);
    }

    /**
     * @param $sortOrder
     * @return MockObject | AbstractModel
     */
    private function getModelMock($sortOrder)
    {
        $model = $this->createMock(AbstractModel::class);
        $model->method('getData')->willReturnMap([
            ['position', null, $sortOrder]
        ]);
        return $model;
    }
}
