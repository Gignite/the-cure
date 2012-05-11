<?php
namespace TheCure\Specs;
/**
 * Test has many relationship
 *
 * @package     TheCure
 * @category    Spec
 * @category    Test
 * @copyright   Gignite, 2012
 * @license     MIT
 * 
 * @group  specs
 * @group  relationships
 * @group  relationships.onetomany
 */
use TheCure\Object;
use TheCure\Models;
use TheCure\Container;
use TheCure\Relationships\HasMany;

class RelationshipHasMany extends \PHPUnit_Framework_TestCase {

	protected function relationship()
	{
		$config = array(
			'mapper_suffix' => 'User',
			'model_suffix' => 'Admin',
		);
		return new HasMany('friends', $config);
	}

	protected function container()
	{
		return new Container('Mock');
	}

	public function testItShouldFindACollectionOfRelatedModels()
	{
		$container = $this->container();
		$model = new Models\User\Admin;
		$model->__object(new Object(array(
			'friends' => array(0, 1),
		)));
		$container->mapper('User')->save($model);
		$collection = $this->relationship()->find($container, $model);
		$this->assertInstanceOf(
			'TheCure\Collections\Collection',
			$collection);
	}

	protected function relation()
	{
		$relation = new Models\User\Admin;
		$relationObject = new Object(array('name' => 'Luke'));
		$relation->__object($relationObject);
		return $relation;
	}

	public function testItShouldSaveAnRelatedObjectWhenAddingRelation()
	{
		$container = $this->container();

		$model = new Models\User\Admin;

		$relation = $this->relation();
		$relationObject = $relation->__object();
		$anotherRelation = $this->relation();

		$relationship = $this->relationship();
		$relationship->add($container, $model, $relation);
		$relationship->add($container, $model, $anotherRelation);

		$userData = $container->mapper('User')->data;
		$this->assertSame($relationObject, current($userData));

		return array($container, $model, $relation);
	}

	/**
	 * @depends testItShouldSaveAnRelatedObjectWhenAddingRelation
	 */
	public function testItShouldAddAnObjectIDToAnotherObjectsArray($args)
	{
		list($container, $model, $relation) = $args;

		$modelObject = $model->__object();
		$relationshipName = $this->relationship()->name();
		$this->assertSame(2, count($modelObject->{$relationshipName}));

		return array($container, $model, $relation);
	}

	/**
	 * @depends testItShouldAddAnObjectIDToAnotherObjectsArray
	 */
	public function testItShouldRemoveAnObjectIDFromAnotherObjectsArray($args)
	{
		list($container, $model, $relation) = $args;

		$relationship = $this->relationship();
		$relationship->remove($container, $model, $relation);

		$modelObject = $model->__object();
		$this->assertSame(1, count($modelObject->{$relationship->name()}));
	}

	/**
	 * @expectedException  TheCure\Relation\FieldNotFoundException
	 */
	public function testItShouldThrowExceptionWhenRelationArrayNotExists()
	{
		$this->relationship()->remove(
			$this->container(),
			new Models\User\Admin,
			new Models\User\Admin);
	}

	/**
	 * @expectedException  TheCure\Relation\NotFoundException
	 */
	public function testItShouldThrowExceptionWhenRelatedObjectIDNotInArray()
	{
		$relationship = $this->relationship();

		$model = new Models\User\Admin;
		$model->__object(new Object(array(
			$relationship->name() => array(),
		)));
		
		$relationship->remove(
			$this->container(),
			$model,
			new Models\User\Admin);
	}

}

