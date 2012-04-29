<?php
use Gignite\TheCure\Mapper\Container;
use Gignite\TheCure\Relationships\OneToOne;

class RelationshipOneToOnelTest extends PHPUnit_Framework_TestCase {

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
		return new Container('Array');
	}

	public function testItShouldFindARelatedModel()
	{
		$container = $this->container();
		$container->mapper('User')->save(new Model_User_Admin);
		$collection = $this->relationship()->find($container, 0);
		$this->assertInstanceOf('Model_User_Admin', $collection);
	}

	public function testItShouldSaveRelationWhenSetting()
	{
		$container = $this->container();

		$model = new Model_User_Admin;

		$relationObject = (object) array('name' => 'Luke');
		$relation = new Model_User_Admin;
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
			new Model_User_Admin,
			new Model_User_Admin);
	}

}

