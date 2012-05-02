<?php
namespace Gignite\TheCure\Specs;

use Gignite\TheCure\Object;
use Gignite\TheCure\Models;
use Gignite\TheCure\Mapper\Container;
use Gignite\TheCure\Relationships\OneToOne;

class RelationshipOneToOne extends \PHPUnit_Framework_TestCase {

	protected function relationship()
	{
		$config = array(
			'mapper_suffix' => 'User',
			'model_suffix' => 'Admin',
		);
		return new OneToOne('best_friend', $config);
	}

	protected function container()
	{
		return new Container('Mock');
	}

	public function testItShouldFindARelatedModel()
	{
		$container = $this->container();
		$container->mapper('User')->save(new Models\User\Admin);
		$collection = $this->relationship()->find($container, 0);
		$this->assertInstanceOf('Gignite\TheCure\Models\User\Admin', $collection);
	}

	public function testItShouldSaveRelationWhenSetting()
	{
		$container = $this->container();

		$model = new Models\User\Admin;

		$relationObject = new Object(array('name' => 'Luke'));
		$relation = new Models\User\Admin;
		$relation->__object($relationObject);

		$this->relationship()->set($container, $model, $relation);
		$this->assertTrue(isset($relationObject->_id));
	
		return array($container, $model, $relation);
	}

	/**
	 * @depends testItShouldSaveRelationWhenSetting
	 */
	public function testItShouldSetRelationOnObject($args)
	{
		list($container, $model, $relation) = $args;

		$modelObject = $model->__object();
		$relationObject = $relation->__object();
		$relationshipName = $this->relationship()->name();

		$this->assertSame(
			$relationObject->_id, 
			$modelObject->{$relationshipName});

		return array($container, $model, $relation);
	}

	/**
	 * @depends testItShouldSetRelationOnObject
	 */
	public function testItShouldRemoveRelationOnObject($args)
	{
		list($container, $model, $relation) = $args;

		$relationship = $this->relationship();
		$relationship->remove($container, $model, $relation);

		$modelObject = $model->__object();
		$this->assertTrue(empty($modelObject->{$relationship->name()}));
	}

	/**
	 * @expectedException  Gignite\TheCure\Relation\FieldNotFoundException
	 */
	public function testItShouldThrowExceptionWhenRelationFieldNotExists()
	{
		$this->relationship()->remove(
			$this->container(),
			new Models\User\Admin,
			new Models\User\Admin);
	}

}

