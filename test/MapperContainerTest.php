<?php
use Gignite\TheCure\Mapper\Container;

class MapperContainerTest extends PHPUnit_Framework_TestCase {

	public function testMapper()
	{
		$container = new Container('Mock');
		$this->assertInstanceOf(
			'Mappers\Mock\User',
			$container->mapper('User'));
	}

	public function testMapperConnection()
	{
		$container = new Container('ConnectionTest');
		$this->assertInstanceOf(
			'Mappers\ConnectionTest\User',
			$container->mapper('User'));
	}

	public function testItShouldSetAndGetConfig()
	{
		$container = new Container('Mock');
		$expectedConfig = array();
		$container->config($expectedConfig);
		$this->assertSame($expectedConfig, $container->config());
	}

	public function testItShouldReturnNullIfNoMapperConfigFound()
	{
		$container = new Container('ConnectionTest');
		$container->config(array(
			'factory' => array(
				'prefixes' => array(
					'connection' => 'Connections',
					'mapper'     => 'Mappers',
				),
				'separator' => '\\',
			),
		));
		$mapper = $container->mapper('User');
	}

}