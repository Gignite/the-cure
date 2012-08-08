<?php
namespace TheCure\Specs;
/**
 * Test abstract mapper interface
 *
 * @package     TheCure
 * @category    Mapper
 * @category    Spec
 * @category    Test
 * @copyright   Gignite, 2012
 * @license     MIT
 * 
 * @group  specs
 * @group  mappers
 * @group  mappers.mapper
 */
use TheCure\TransferObjects\TransferObject;
use TheCure\Accessors\TransferObjectAccessor;
use TheCure\Models;

abstract class MapperTest extends \PHPUnit_Framework_TestCase {

	public function providerTestFindOne()
	{
		$mapper = static::mapper();
		$mapper2 = static::mapper();
		$data = static::prepareData($mapper);
		$data2 = static::prepareData($mapper2);

		if ($data && $data2)
		{
			$id = (string) $data->_id;
			$id2 = (string) $data2->_id;
			$suffix = 'Admin';
			$where = array('name' => 'Luke');

			$where2 = array(
				'_id' => array('$in' => array($id, $id2)),
			);

			return array(
				array($mapper,  NULL,    NULL,    "TheCure\\Models\\User"),
				array($mapper,  $id,     NULL,    "TheCure\\Models\\User"),
				array($mapper,  $where,  NULL,    "TheCure\\Models\\User"),
				array($mapper,  $where2, NULL,    "TheCure\\Models\\User"),
				array($mapper2, NULL,    $suffix, "TheCure\\Models\\User\\{$suffix}"),
				array($mapper2, $id2,    $suffix, "TheCure\\Models\\User\\{$suffix}"),
				array($mapper2, $where,  $suffix, "TheCure\\Models\\User\\{$suffix}"),
			);
		}
	}

	/**
	 * @dataProvider  providerTestFindOne
	 */
	public function testFindOne($mapper, $id, $suffix, $expectedClass)
	{
		$model = $mapper->findOne($id, $suffix);
		$this->assertInstanceOf($expectedClass, $model);
	}

	public function testFindOneNone()
	{
		$this->assertNull(static::mapper()->findOne(array('foo' => 'bar')));
	}

	public function providerTestFind()
	{
		$mapper = static::mapper();
		static::prepareData($mapper);
		$suffix = 'Admin';
		$where = array('name' => 'Luke');

		return array(
			array($mapper, NULL,   NULL,    FALSE),
			array($mapper, $where, NULL,    FALSE),
			array($mapper, $where, $suffix, FALSE),
		);
	}

	/**
	 * @dataProvider  providerTestFind
	 */
	public function testFind($mapper, $id, $suffix, $exception)
	{
		$collection = $mapper->find($id, $suffix);
		$this->assertTrue($collection->count() > 0);
	}

	public function testFindNone()
	{
		$collection = static::mapper()->find(array('foo' => 'bar'));
		$this->assertSame(0, $collection->count());
		$this->assertNull($collection->current());
	}

	public function testFindOneWithNonExistentId()
	{
		$result = static::mapper()->findOne(123);
		$this->assertNull($result);
	}

	public function provideModel()
	{
		$model = new Models\User;
		$accessor = new TransferObjectAccessor;
		$accessor->set($model, array('name' => 'Luke'));

		return array(
			array($model),
		);
	}

	/**
	 * @dataProvider  provideModel
	 */
	public function testSave($model)
	{
		$mapper = static::mapper();
		$mapper->save($model);

		$accessor = new TransferObjectAccessor;
		$object = $accessor->get($mapper->find()->current());

		$this->assertTrue(isset($object->_id));
		$this->assertSame('Luke', $object->name);
	}

	/**
	 * @dataProvider  provideModel
	 */
	public function testUpdate($model)
	{
		$mapper = static::mapper();
		$mapper->save($model);

		$model = $mapper->findOne();
		$mapper->save($model);
		$this->assertSame($model, $mapper->findOne());
	}

	public function testDeleteWithModel()
	{
		// PREP
		$mapper = static::mapper();
		$expectedCount = $mapper->find()->count();

		$model = new Models\User;
		$accessor = new TransferObjectAccessor;
		$accessor->set($model, array('name' => 'Luke'));

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
			return new TransferObject(array('name' => 'Bob'));
		};

		$expectedCount = $mapper->find($query)->count();

		$accessor = new TransferObjectAccessor;

		// Create two users
		$model = new Models\User;
		$accessor->set($model, $bobObject());
		$mapper->save($model);
		
		$model = new Models\User;
		$accessor->set($model, $bobObject());
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
			return new TransferObject(array('name' => 'Jim'));
		};

		$expectedCount = $mapper->find($query)->count();
		
		$accessor = new TransferObjectAccessor;

		// Create two users
		$model = new Models\User;
		$accessor->set($model, $bobObject());
		$mapper->save($model);
		
		$model = new Models\User;
		$accessor->set($model, $bobObject());
		$mapper->save($model);

		$this->assertSame(2, $mapper->find($query)->count());
		// END PREP

		$mapper->delete($query);
		$this->assertSame(0, $mapper->find($query)->count());
	}

}