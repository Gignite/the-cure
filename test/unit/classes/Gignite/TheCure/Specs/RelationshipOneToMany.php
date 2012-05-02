<?php
namespace Gignite\TheCure\Specs;

use Gignite\TheCure\Object;
use Gignite\TheCure\Models;
use Gignite\TheCure\Mapper\Container;
use Gignite\TheCure\Relationships\OneToMany;

class RelationshipOneToMany extends \PHPUnit_Framework_TestCase {

	protected function relationship()
	{
		$config = array(
			'mapper_suffix' => 'User',
			'model_suffix' => 'Admin',
		);
		return new OneToMany('friends', $config);
	}

	protected function container()
	{
		return new Container('Mock');
	}

	public function testItShouldFindACollectionOfRelatedModels()
	{
		$container = $this->container();
		$container->mapper('User')->save(new Models\User\Admin);
		$collection = $this->relationship()->find($container, array(0, 1));
		$this->assertInstanceOf(
			'Gignite\TheCure\Collections\Collection',
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
	 * @expectedException  Gignite\TheCure\Relation\FieldNotFoundException
	 */
	public function testItShouldThrowExceptionWhenRelationArrayNotExists()
	{
		$this->relationship()->remove(
			$this->container(),
			new Models\User\Admin,
			new Models\User\Admin);
	}

	/**
	 * @expectedException  Gignite\TheCure\Relation\NotFoundException
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

