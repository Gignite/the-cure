<?php
namespace Gignite\TheCure\Specs;

use Gignite\TheCure\Object;
use Gignite\TheCure\Models;
use Gignite\TheCure\Relationships;
use Gignite\TheCure\Container;

class ModelMagic extends \PHPUnit_Framework_TestCase {

	protected $container;

	protected function container()
	{
		if ($this->container === NULL)
		{
			$container = new Container('Mock');

			$jim = $container->mapper('User')->model('Magic');
			$jim->__object(new Object(array(
				'name' => 'Jim',
			)));
			$container->mapper('User')->save($jim);

			$luke = $container->mapper('User')->model('Magic');
			$luke->__object(new Object(array(
				'name'    => 'Luke',
				'friends' => array($jim->__object()->_id),
			)));
			$container->mapper('User')->save($luke);

			$this->container = $container;
		}
		
		return $this->container;
	}

	protected function user($expectedName)
	{
		$container = $this->container();
		return $container->mapper('User')->find_one(
			array('name' => $expectedName),
			'Magic');
	}

	public function providerTestMagicCall()
	{
		$expectedName = 'Luke';
		$luke = $this->user($expectedName);

		return array(
			array($luke, $expectedName, array()),
			array($luke, $expectedName = 'Bob', array($expectedName)),
		);
	}

	/**
	 * @dataProvider  providerTestMagicCall
	 */
	public function testMagicCall($model, $expectedName, $args)
	{
		if ($args)
		{
			call_user_func_array(array($model, 'name'), $args);
		}

		$this->assertSame($expectedName, $model->name());
	}

	public function testItShouldUseDefaultValueWhenNoValueSet()
	{
		$this->assertSame(1, $this->user('Jim')->age());
	}

	public function providerModelWithMockableRelation()
	{
		$args = array(new Models\User);

		return array(
			array(
				new Models\User\MockableRelation,
				'relation',
				'find',
				array(),
			),
			array(
				new Models\User\MockableRelation,
				'add_relation',
				'add',
				$args,
			),
			array(
				new Models\User\MockableRelation,
				'remove_relation',
				'remove',
				$args,
			),
			array(
				new Models\User\MockableRelation,
				'relation',
				'set',
				$args,
			),
		);
	}

	/**
	 * @dataProvider  providerModelWithMockableRelation
	 */
	public function testMagicCallRelationship($model, $method, $expected, $args)
	{
		$mock = new Relationships\Mock('relation');

		Models\User\MockableRelation::$relation = function () use ($mock)
		{
			return $mock;
		};

		call_user_func_array(array($model, $method), $args);
		$this->assertSame($expected, $mock->method_called());
	}

	/**
	 * @expectedException  BadMethodCallException
	 */
	public function testItShouldThrowBadMethodCallException()
	{
		$model = new Models\User\Magic;
		$model->unknown();
	}

	public function provideAccessorMethods()
	{
		return array(
			array('name',     'name', 'Luke'),
			array('location', 'town', 'Braintree'),
		);
	}

	/**
	 * @dataProvider  provideAccessorMethods
	 */
	public function testItShouldUseAccessorMethodName($name, $alias, $value)
	{
		$model = new Models\User\Magic;
		$model->__object(new Object(array(
			$name => $value,
		)));
		$this->assertSame($value, $model->{$alias}());
	}

}

