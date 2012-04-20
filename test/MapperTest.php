<?php

abstract class MapperTest extends PHPUnit_Framework_TestCase {

	abstract public function mapper();

	protected static function first_document()
	{
		return static::collection(static::db())->find()->current();
	}

	public function providerTestFind()
	{
		$suffix = NULL;
		$where = array('name' => 'Luke');

		return array(
			array(NULL,    NULL),
			array($where,  NULL),
			array($suffix, $where),
			array($suffix, NULL),
		);
	}

	/**
	 * @dataProvider  providerTestFind
	 */
	public function testFind($suffix, $id)
	{
		$collection = $this->mapper()->find($suffix, $id);
		$this->assertTrue($this->mapper()->find()->count() > 0);
	}

	// public function providerTestFindOne()
	// {
	// 	$id = static::first_document()->_id;
	// 	$suffix = NULL;
	// 	$where = array('name' => 'Luke');

	// 	return array(
	// 		array($id,     NULL),
	// 		array($suffix, $id),
	// 		array($where,  NULL),
	// 		array($suffix, $where),
	// 	);
	// }

	// /**
	//  * @dataProvider  providerTestFindOne
	//  */
	// public function testFindOne($suffix, $id)
	// {
	// 	$model = $this->mapper()->find_one($suffix, $id);
	// }

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
		$this->mapper()->save($model);

		$object = $this->mapper()->find()->current()->__object();

		$this->assertInstanceOf('MongoID', $object->_id);
		$this->assertSame('Luke', $object->name);
	}

	// public function providerTestDelete()
	// {
	// 	return array(
	// 		array($model),
	// 		array($collection),
	// 		array($id),
	// 		array($where),
	// 	);
	// }

	// /**
	//  * @dataProvider  providerTestDelete
	//  */
	// public function testDelete($criteria)
	// {
	// 	$this->mapper()->delete($criteria);
	// }

}