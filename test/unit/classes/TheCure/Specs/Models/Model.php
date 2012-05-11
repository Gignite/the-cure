<?php
namespace TheCure\Specs;

use TheCure\Models;

class ModelTest extends \PHPUnit_Framework_TestCase {

	public function testDefaultObjectIsStdClass()
	{
		$model = new Models\User;
		$this->assertInstanceOf('TheCure\Object', $model->__object());
	}

}