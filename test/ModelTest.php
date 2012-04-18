<?php

class ModelTest extends PHPUnit_Framework_TestCase {

	public function testDefaultObjectIsStdClass()
	{
		$model = new Model_User;
		$this->assertInstanceOf('StdClass', $model->__object());
	}

}