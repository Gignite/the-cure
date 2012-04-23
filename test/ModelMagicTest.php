<?php

class ModelMagicTest extends PHPUnit_Framework_TestCase {

	protected $container;

	protected function container()
	{
		if ($this->container === NULL)
		{
			Mapper_Array::$data = array();
			$container = new MapperContainer('Array');

			$jim = new Model_User_Magic;
			$jim->__container($container);
			$jim->__object((object) array(
				'name' => 'Jim',
			));
			$container->mapper('User')->save($jim);


			$luke = new Model_User_Magic;
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

	// public function providerTestMagicCall()
	// {
	// 	$expectedName = 'Luke';
	// 	$luke = $this->user($expectedName);

	// 	return array(
	// 		array($luke, $expectedName),
	// 	);
	// }

	// /**
	//  * @dataProvider  providerTestMagicCall
	//  */
	// public function testMagicCall($model, $expectedName)
	// {
	// 	$this->assertSame($expectedName, $model->name());
	// }

	public function providerTestMagicCallRelationshipFind()
	{
		$luke = $this->user('Luke');
		$jim = $this->user('Jim');

		return array(
			array($luke, $jim),
		);
	}

	/**
	 * @dataProvider  providerTestMagicCallRelationshipFind
	 */
	public function testMagicCallRelationshipFind($model, $expectedRelation)
	{
		$this->assertSame($expectedRelation, $model->friends()->current());
	}

}

