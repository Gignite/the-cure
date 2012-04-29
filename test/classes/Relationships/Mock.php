<?php
namespace Relationships;

use Gignite\TheCure\Mapper\Container;
use Gignite\TheCure\Relationships\Relationship;
use Gignite\TheCure\Relation;

class Mock extends Relationship
	implements Relation\Add, Relation\Remove, Relation\Set {

	protected $method_called;

	public function method_called()
	{
		return $this->method_called;
	}

	public function find(Container $container, $value)
	{
		$this->method_called = 'find';
	}

	public function add(Container $container, $object, $relation)
	{
		$this->method_called = 'add';
	}

	public function remove(Container $container, $object, $relation)
	{
		$this->method_called = 'remove';
	}

	public function set(Container $container, $object, $relation)
	{
		$this->method_called = 'set';
	}

}