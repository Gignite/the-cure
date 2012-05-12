<?php
namespace TheCure\Relationships;

use TheCure\Container;
use TheCure\Relationships\Relationship;
use TheCure\Relation;
use TheCure\Models\Model;

class Mock extends Relationship
	implements Relation\Find, Relation\Add, Relation\Remove,
		Relation\Delete, Relation\Set {

	protected $method_called;

	public function method_called()
	{
		return $this->method_called;
	}

	public function find(Container $container, Model $object)
	{
		$this->method_called = 'find';
	}

	public function add(Container $container, Model $object, Model $relation)
	{
		$this->method_called = 'add';
	}

	public function remove(Container $container, Model $object, Model $relation)
	{
		$this->method_called = 'remove';
	}

	public function delete(Container $container, Model $object)
	{
		$this->method_called = 'delete';
	}

	public function set(Container $container, Model $object, Model $relation)
	{
		$this->method_called = 'set';
	}

}