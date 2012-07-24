<?php
namespace TheCure\Specs;
/**
 * Test has many relationship
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
 * @group  relationships.hasmany
 */
use TheCure\TransferObjects\TransferObjects;

use TheCure\Accessors\TransferObjectAccessor;

use TheCure\Models;

use TheCure\Container;

use TheCure\Relationships\HasManyRelationship;

class RelationshipHasMany extends \PHPUnit_Framework_TestCase {

	protected function relationship()
	{
		$config = array(
			'mapper' => 'User',
			'modelSuffix' => 'Admin',
		);
		return new HasManyRelationship('friends', $config);
	}

	protected function container()
	{
		return new Container('Mock');
	}

	public function testItShouldFindACollectionOfRelatedModels()
	{
		$container = $this->container();
		$model = new Models\User\Admin;
		$accessor = new TransferObjectAccessor;
		$accessor->set($model, array('friends' => array(0, 1)));
		$container->mapper('User')->save($model);
		$collection = $this->relationship()->find($container, $model);
		$this->assertInstanceOf(
			'TheCure\Collections\Collection',
			$collection);
	}

	protected function relation()
	{
		$relation = new Models\User\Admin;
		$accessor = new TransferObjectAccessor;
		$accessor->set($relation, array('name' => 'Luke'));
		return $relation;
	}

	public function testItShouldSaveAnRelatedObjectWhenAddingRelation()
	{
		$container = $this->container();

		$model = new Models\User\Admin;

		$relation = $this->relation();
		$accessor = new TransferObjectAccessor;
		$relationObject = $accessor->get($relation);
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
	public function testItShouldReturnTrueIfARelationContainsAnObject($args)
	{
		list($container, $model, $relation) = $args;

		$result = $this->relationship()->contains(
			$container,
			$model,
			$relation);

		$this->assertTrue($result);

		return array($container, $model, $relation);
	}

	/**
	 * @depends testItShouldReturnTrueIfARelationContainsAnObject
	 */
	public function testItShouldAddAnObjectIDToAnotherObjectsArray($args)
	{
		list($container, $model, $relation) = $args;

		$accessor = new TransferObjectAccessor;
		$modelObject = $accessor->get($model);
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

		$accessor = new TransferObjectAccessor;
		$modelObject = $accessor->get($model);
		$this->assertSame(1, count($modelObject->{$relationship->name()}));

		return array($container, $model, $relation);
	}

	/**
	 * @depends testItShouldRemoveAnObjectIDFromAnotherObjectsArray
	 */
	public function testItShouldReturnFalseIfARelationDoesntContainAnObject($args)
	{
		list($container, $model, $relation) = $args;

		$result = $this->relationship()->contains(
			$container,
			$model,
			$relation);
		
		$this->assertFalse($result);
	}

	/**
	 * @expectedException  TheCure\Exceptions\FieldNotFoundException
	 */
	public function testItShouldThrowExceptionWhenRelationArrayNotExists()
	{
		$this->relationship()->remove(
			$this->container(),
			new Models\User\Admin,
			new Models\User\Admin);
	}

	/**
	 * @expectedException  TheCure\Exceptions\NotFoundException
	 */
	public function testItShouldThrowExceptionWhenRelatedObjectIDNotInArray()
	{
		$relationship = $this->relationship();

		$model = new Models\User\Admin;
		$accessor = new TransferObjectAccessor;
		$accessor->set($model, array(
			$relationship->name() => array(),
		));
		
		$relationship->remove(
			$this->container(),
			$model,
			new Models\User\Admin);
	}

}

