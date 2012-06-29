<?php
namespace TheCure\Relationships;

use TheCure\Container;
use TheCure\Relationships\Relationship;
use TheCure\Relation;
use TheCure\Models\Model;

class Mock extends Relationship
	implements Relation\Find, Relation\Add, Relation\Remove,
		Relation\Delete, Relation\Set {

	protected $methodCalled;

	public function methodCalled()
	{
		return $this->methodCalled;
	}

	public function find(Container $container, Model $object)
	{
		$this->methodCalled = 'find';
	}

	public function add(Container $container, Model $object, Model $relation)
	{
		$this->methodCalled = 'add';
	}

	public function remove(Container $container, Model $object, Model $relation)
	{
		$this->methodCalled = 'remove';
	}

	public function delete(Container $container, Model $object)
	{
		$this->methodCalled = 'delete';
	}

	public function set(Container $container, Model $object, Model $relation)
	{
		$this->methodCalled = 'set';
	}

}