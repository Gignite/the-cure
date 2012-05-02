<?php
namespace Gignite\TheCure\Specs;

use Gignite\TheCure\Models;

class ModelTest extends \PHPUnit_Framework_TestCase {

	public function testDefaultObjectIsStdClass()
	{
		$model = new Models\User;
		$this->assertInstanceOf('Gignite\TheCure\Object', $model->__object());
	}

}