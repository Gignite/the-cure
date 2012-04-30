<?php
use Gignite\TheCure\Object;

class ObjectTest extends PHPUnit_Framework_TestCase {

	public function testItShouldFilterArray()
	{
		$data = array('name' => 'Luke', 'age' => 22);
		$filter = array('name');
		$object = new Object($data, $filter);
		$expected = array('name' => 'Luke');
		$this->assertSame($expected, $object->as_array());
	}

}

