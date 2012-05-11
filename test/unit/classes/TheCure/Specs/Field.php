<?php
namespace TheCure\Specs;

/**
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

