<?php
namespace TheCure\Specs;
/**
 * Test has one relationship
 *
 * @package     TheCure
 * @category    Relationship
 * @category    Spec
 * @category    Test
 * @copyright   Gignite, 2012
 * @license     MIT
 * 
 * @group  specs
 * @group  relationships
 * @group  relationships.hasone
 */
use TheCure\TransferObjects\TransferObject;

use TheCure\Accessors\TransferObjectAccessor;

use TheCure\Models;

use TheCure\Container;

use TheCure\Relationships\HasOneRelationship;

class RelationshipHasOne extends \PHPUnit_Framework_TestCase {

	protected function relationship()
	{
		$config = array(
			'mapper' => 'User',
			'modelSuffix' => 'Admin',
		);
		return new HasOneRelationship('best_friend', $config);
	}

	protected function container()
	{
		return new Container('Mock');
	}

	public function testItShouldFindARelatedModel()
	{
		$container = $this->container();
		$model = new Models\User\Admin;
		$accessor = new TransferObjectAccessor;
		$accessor->set($model, array('best_friend' => 0));
		$container->mapper('User')->save($model);
		$collection = $this->relationship()->find($container, $model);
		$this->assertInstanceOf('TheCure\Models\User\Admin', $collection);
	}

	public function testItShouldSaveRelationWhenSetting()
	{
		$container = $this->container();

		$model = new Models\User\Admin;

		$relation = new Models\User\Admin;
		$accessor = new TransferObjectAccessor;
		$accessor->set($relation, array('name' => 'Luke'));

		$this->relationship()->set($container, $model, $relation);
		$this->assertTrue(isset($accessor->get($relation)->_id));
	
		return array($container, $model, $relation);
	}

	/**
	 * @depends testItShouldSaveRelationWhenSetting
	 */
	public function testItShouldSetRelationOnObject($args)
	{
		list($container, $model, $relation) = $args;

		$accessor = new TransferObjectAccessor;
		$modelObject = $accessor->get($model);
		$relationObject = $accessor->get($relation);
		$relationshipName = $this->relationship()->name();

		$this->assertSame(
			$relationObject->_id, 
			$modelObject->{$relationshipName});

		return array($container, $model, $relation);
	}

	/**
	 * @depends testItShouldSetRelationOnObject
	 */
	public function testItShouldDeleteRelationOnObject($args)
	{
		list($container, $model, $relation) = $args;

		$relationship = $this->relationship();
		$relationship->delete($container, $model);

		$accessor = new TransferObjectAccessor;
		$modelObject = $accessor->get($model);
		$this->assertTrue(empty($modelObject->{$relationship->name()}));
	}

	/**
	 * @expectedException  TheCure\Exceptions\FieldNotFoundException
	 */
	public function testItShouldThrowExceptionWhenRelationFieldNotExists()
	{
		$this->relationship()->delete($this->container(), new Models\User\Admin);
	}

}

