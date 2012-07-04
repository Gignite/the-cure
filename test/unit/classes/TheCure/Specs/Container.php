<?php
namespace TheCure\Specs;
/**
 * Test the cure's main dependency container
 *
 * @package     TheCure
 * @category    Container
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
			'TheCure\Mappers\Mock\UserMapper',
			$container->mapper('User'));
	}

	public function testMapperConnection()
	{
		$container = new Container('Test');
		$this->assertInstanceOf(
			'TheCure\Mappers\Test\UserMapper',
			$container->mapper('User'));
	}

	public function testItShouldSetAndGetConfig()
	{
		$container = new Container('Mock');
		$expectedConfig = array();
		$container->config($expectedConfig);
		$this->assertSame($expectedConfig, $container->config());
	}

	public function testItShouldUseFactoryIfNoMapperConfigFound()
	{
		$container = new Container('Test');
		$container->config(array(
			'factory' => array(
				'prefixes' => array(
					'connection' => 'TheCure\Connections\\',
					'mapper'     => 'TheCure\Mappers\\',
				),
				'suffixes' => array(
					'connection' => 'Connection',
					'mapper'     => 'Mapper',
				),
				'separator' => '\\',
			),
		));
		$mapper = $container->mapper('User');
		$this->assertInstanceOf('TheCure\Mappers\Test\UserMapper', $mapper);
	}

	public function testItShouldLoadDefaultConfigIfNoKohanaAndNoConfigSet()
	{
		$config = \Kohana::$config;
		\Kohana::$config = NULL;
		$container = new Container('Test');
		$this->assertSame(
			require(APPPATH.'/../../config/the-cure.php'),
			$container->config());
		\Kohana::$config = $config;
	}

}