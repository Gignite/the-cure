<?php
namespace TheCure\Specs;
/**
 * Test the cure's main dependency container
 *
 * @package     TheCure
 * @category    Spec
 * @category    Test
 * @copyright   Gignite, 2012
 * @license     MIT
 * 
 * @group  specs
 * @group  container
 */
use TheCure\Container;

class ContainerTest extends \PHPUnit_Framework_TestCase {

	public function testMapper()
	{
		$container = new Container('Mock');
		$this->assertInstanceOf(
			'TheCure\Mappers\Mock\User',
			$container->mapper('User'));
	}

	public function testMapperConnection()
	{
		$container = new Container('ConnectionTest');
		$this->assertInstanceOf(
			'TheCure\Mappers\ConnectionTest\User',
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
					'connection' => 'TheCure\Connections',
					'mapper'     => 'TheCure\Mappers',
				),
				'separator' => '\\',
			),
		));
		$mapper = $container->mapper('User');
	}

}