<?php

class MapperContainerTest extends PHPUnit_Framework_TestCase {

	public function testMapper()
	{
		$container = new MapperContainer('Array');
		$this->assertInstanceOf(
			'Mapper_Array_User',
			$container->mapper('User'));
	}

	public function testMapperConnection()
	{
		$container = new MapperContainer('ConnectionTest');
		$this->assertInstanceOf(
			'Mapper_ConnectionTest_User',
			$container->mapper('User'));
	}

}