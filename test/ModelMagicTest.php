<?php
use Gignite\TheCure\Mapper\Container;

class ModelMagicTest extends PHPUnit_Framework_TestCase {

	protected $container;

	protected function container()
	{
		if ($this->container === NULL)
		{
			$container = new Container('Mock');

			$jim = new Models\User\Magic;
			$jim->__container($container);
			$jim->__object((object) array(
				'name' => 'Jim',
			));
			$container->mapper('User')->save($jim);

			$luke = new Models\User\Magic;
			$luke->__container($container);
			$luke->__object((object) array(
				'name'    => 'Luke',
				'friends' => array($jim->__object()->_id),
			));
			$container->mapper('User')->save($luke);

			$this->container = $container;
		}
		
		return $this->container;
	}

	protected function user($expectedName)
	{
		$container = $this->container();
		return $container->mapper('User')->find_one('Magic', array(
			'name' => $expectedName,
		));
	}

	public function providerTestMagicCall()
	{
		$expectedName = 'Luke';
		$luke = $this->user($expectedName);

		return array(
			array($luke, $expectedName, array()),
			array($luke, $expectedName, array($expectedName)),
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

}

