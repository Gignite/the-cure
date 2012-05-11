<?php
namespace TheCure\Specs;
/**
 * Test the data transfer object
 *
 * @package     TheCure
 * @category    Spec
 * @category    Test
 * @copyright   Gignite, 2012
 * @license     MIT
 * 
 * @group  specs
 * @group  object
 */
use TheCure\Object;

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

	public function testItShouldUnsetField()
	{
		$object = new Object(array('name' => 'Luke'));
		unset($object->name);
		$this->assertSame(array(), $object->as_array());
	}

}

