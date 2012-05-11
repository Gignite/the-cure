<?php
namespace TheCure\Specs;
/**
 * Test a base field class
 *
 * @package     TheCure
 * @category    Spec
 * @category    Test
 * @copyright   Gignite, 2012
 * @license     MIT
 * 
 * @group  specs
 * @group  attribute
 * @group  field
 */
use TheCure\Field;

class FieldTest extends \PHPUnit_Framework_TestCase {

	public function testItShouldGetRulesIfTheyExist()
	{
		$rules = array(array('not_empty'));
		$object = new Field('name', compact('rules'));
		$this->assertSame($rules, $object->rules());
	}
	
}

