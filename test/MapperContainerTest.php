<?php
use Gignite\TheCure\Mapper\Container;

class MapperContainerTest extends PHPUnit_Framework_TestCase {

	public function testMapper()
	{
		$container = new Container('Array');
		$this->assertInstanceOf(
			'Mapper_Array_User',
			$container->mapper('User'));
	}

	public function testMapperConnection()
	{
		$container = new Container('ConnectionTest');
		$this->assertInstanceOf(
			'Mapper_ConnectionTest_User',
			$container->mapper('User'));
	}

	public function testItShouldSetAndGetConfig()
	{
		$container = new Container('Array');
		$expectedConfig = array();
		$container->config($expectedConfig);
		$this->assertSame($expectedConfig, $container->config());
	}

}