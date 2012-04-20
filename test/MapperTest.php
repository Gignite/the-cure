<?php

abstract class MapperTest extends PHPUnit_Framework_TestCase {

	public function providerTestFindOne()
	{
		$data = static::prepareData();

		$id = $data->_id;
		$suffix = 'Admin';
		$where = array('name' => 'Luke');

		return array(
			array(NULL,    NULL),
			array($id,     NULL),
			array($suffix, $id),
			array($where,  NULL),
			array($suffix, $where),
		);
	}

	/**
	 * @dataProvider  providerTestFindOne
	 */
	public function testFindOne($suffix, $id)
	{
		$model = static::mapper()->find_one($suffix, $id);

		$classExpected = 'Model_User';
		if (is_string($suffix))
		{
			$classExpected .= "_{$suffix}";
		}

		$this->assertInstanceOf($classExpected, $model);
	}

	public function providerTestFind()
	{
		$suffix = 'Admin';
		$where = array('name' => 'Luke');

		return array(
			array(NULL,    NULL,   FALSE),
			array($where,  NULL,   FALSE),
			array($suffix, $where, FALSE),
			array($suffix, NULL,   FALSE),
			array(100,     NULL,   'InvalidArgumentException'),
		);
	}

	/**
	 * @dataProvider  providerTestFind
	 */
	public function testFind($suffix, $id, $exception)
	{
		if ($exception)
		{
			$this->setExpectedException($exception);
		}

		$collection = static::mapper()->find($suffix, $id);
		$this->assertTrue(static::mapper()->find()->count() > 0);
	}

	public function providerTestSave()
	{
		$model = new Model_User;
		$model->__object((object) array('name' => 'Luke'));

		return array(
			array($model),
		);
	}

	/**
	 * @dataProvider  providerTestSave
	 */
	public function testSave($model)
	{
		static::mapper()->save($model);

		$object = static::mapper()->find()->current()->__object();

		$this->assertTrue(isset($object->_id));
		$this->assertSame('Luke', $object->name);
	}

	public function testUpdate()
	{
		$mapper = static::mapper();
		$model = $mapper->find_one();
		$mapper->save($model);
		$this->assertSame($model, $mapper->find_one());
	}

	// public function providerTestDelete()
	// {
	// 	return array(
	// 		array(),
	// 		array(static::mapper()->find()),
	// 		array(static::mapper()->find_one()->__object()->_id),
	// 		array(array('name' => 'Luke')),
	// 	);
	// }

	public function testDeleteWithModel()
	{
		$mapper = static::mapper();
		$expectedCount = $mapper->find()->count();

		$model = new Model_User;
		$model->__object((object) array('name' => 'Luke'));

		$mapper->save($model);
		$this->assertSame($expectedCount + 1, $mapper->find()->count());

		$mapper->delete($model);
		$this->assertSame($expectedCount, $mapper->find()->count());
	}

}