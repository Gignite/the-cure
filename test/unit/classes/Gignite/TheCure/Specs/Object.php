<?php
namespace Gignite\TheCure\Specs;

/**
 * @group  specs
 * @group  object
 */

use Gignite\TheCure\Object;

class ObjectTest extends \PHPUnit_Framework_TestCase {

	public function testItShouldFilterArray()
	{
		$data = array('name' => 'Luke', 'age' => 22);
		$filter = array('name');
		$object = new Object($data, $filter);
		$expected = array('name' => 'Luke');
		$this->assertSame($expected, $object->as_array());
	}

	public function testItShouldReturnNullIfFieldNotSet()
	{
		$object = new Object;
		$this->assertNull($object->get('unknown'));
	}

	public function testItShouldGetViaAccessor()
	{
		$object = new Object(array('name' => 'Luke'));
		$this->assertSame('Luke', $object->accessor('name'));
	}

	public function testItShouldSetViaAccessor()
	{
		$object = new Object(array('name' => 'Luke'));
		$object->accessor('name', 'Jake');
		$this->assertSame('Jake', $object->name);
	}

	public function testItShouldSetAnArrayOfDataIfGivenAnArray()
	{
		$object = new Object;
		$object->set($expected = array('name' => 'Bob'));
		$this->assertSame($expected, $object->as_array());
	}

	public function testItShouldReturnAnArrayIfGivenAnArrayOfFields()
	{
		$object = new Object(array('name' => 'Luke'));
		$this->assertSame(array('name' => 'Luke'), $object->get(array('name')));
	}

}

