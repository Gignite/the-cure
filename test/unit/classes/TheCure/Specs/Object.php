<?php
namespace TheCure\Specs;
/**
 * Test the data transfer object
 *
 * @package     TheCure
 * @category    Object
 * @category    Spec
 * @category    Test
 * @copyright   Gignite, 2012
 * @license     MIT
 * 
 * @group  specs
 * @group  object
 */
use TheCure\TransferObjects\TransferObject;

class ObjectTest extends \PHPUnit_Framework_TestCase {

	public function testItShouldFilterArray()
	{
		$data = array('name' => 'Luke', 'age' => 22);
		$filter = array('name');
		$object = new TransferObject($data, $filter);
		$expected = array('name' => 'Luke');
		$this->assertSame($expected, $object->asArray());
	}

	public function testItShouldReturnNullIfFieldNotSet()
	{
		$object = new TransferObject;
		$this->assertNull($object->get('unknown'));
	}

	public function testItShouldGetViaAccessor()
	{
		$object = new TransferObject(array('name' => 'Luke'));
		$this->assertSame('Luke', $object->accessor('name'));
	}

	public function testItShouldSetViaAccessor()
	{
		$object = new TransferObject(array('name' => 'Luke'));
		$object->accessor('name', 'Jake');
		$this->assertSame('Jake', $object->name);
	}

	public function testItShouldSetAnArrayOfDataIfGivenAnArray()
	{
		$object = new TransferObject;
		$object->set($expected = array('name' => 'Bob'));
		$this->assertSame($expected, $object->asArray());
	}

	public function testItShouldReturnAnArrayIfGivenAnArrayOfFields()
	{
		$object = new TransferObject(array('name' => 'Luke'));
		$this->assertSame(array('name' => 'Luke'), $object->get(array('name')));
	}

	public function testItShouldUnsetField()
	{
		$object = new TransferObject(array('name' => 'Luke'));
		unset($object->name);
		$this->assertSame(array(), $object->asArray());
	}

}

