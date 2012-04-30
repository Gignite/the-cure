<?php
use Gignite\TheCure\Object;

abstract class MapperTest extends PHPUnit_Framework_TestCase {

	public function providerTestFindOne()
	{
		$data = static::prepareData();

		$id = $data->_id;
		$suffix = 'Admin';
		$where = array('name' => 'Luke');

		return array(
			array(NULL,    NULL,   "Models\\User"),
			array($id,     NULL,   "Models\\User"),
			array($suffix, NULL,   "Models\\User\\{$suffix}"),
			array($suffix, $id,    "Models\\User\\{$suffix}"),
			array($where,  NULL,   "Models\\User"),
			array($suffix, $where, "Models\\User\\{$suffix}")
		);
	}

	/**
	 * @dataProvider  providerTestFindOne
	 */
	public function testFindOne($suffix, $id, $expectedClass)
	{
		$model = static::mapper()->find_one($suffix, $id);
		$this->assertInstanceOf($expectedClass, $model);
	}

	public function testFindOneNone()
	{
		$this->assertNull(static::mapper()->find_one(array('foo' => 'bar')));
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
		$this->assertTrue($collection->count() > 0);
	}

	public function testFindNone()
	{
		$collection = static::mapper()->find(array('foo' => 'bar'));
		$this->assertSame(0, $collection->count());
		$this->assertNull($collection->current());
	}

	public function providerTestSave()
	{
		$model = new Models\User;
		$model->__object(new Object(array('name' => 'Luke')));

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

	public function testDeleteWithModel()
	{
		// PREP
		$mapper = static::mapper();
		$expectedCount = $mapper->find()->count();

		$model = new Models\User;
		$model->__object(new Object(array('name' => 'Luke')));

		$mapper->save($model);
		$this->assertSame($expectedCount + 1, $mapper->find()->count());
		// END PREP

		$mapper->delete($model);
		$this->assertSame($expectedCount, $mapper->find()->count());
	}

	public function testDeleteWithCollection()
	{
		// PREP
		$mapper = static::mapper();

		$query = array('name' => 'Bob');
		$bobObject = function ()
		{
			return new Object(array('name' => 'Bob'));
		};

		$expectedCount = $mapper->find($query)->count();

		// Create two users
		$model = new Models\User;
		$model->__object($bobObject());
		$mapper->save($model);
		
		$model = new Models\User;
		$model->__object($bobObject());
		$mapper->save($model);

		$this->assertSame(2, $mapper->find($query)->count());
		// END PREP

		$mapper->delete($mapper->find($query));
		$this->assertSame(0, $mapper->find($query)->count());
	}

	public function testDeleteWithQuery()
	{
		// PREP
		$mapper = static::mapper();

		$query = array('name' => 'Jim');
		$bobObject = function ()
		{
			return new Object(array('name' => 'Jim'));
		};

		$expectedCount = $mapper->find($query)->count();

		// Create two users
		$model = new Models\User;
		$model->__object($bobObject());
		$mapper->save($model);
		
		$model = new Models\User;
		$model->__object($bobObject());
		$mapper->save($model);

		$this->assertSame(2, $mapper->find($query)->count());
		// END PREP

		$mapper->delete($query);
		$this->assertSame(0, $mapper->find($query)->count());
	}

}