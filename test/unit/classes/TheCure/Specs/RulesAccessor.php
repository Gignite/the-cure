<?php
namespace TheCure\Specs;
/**
 * @package     TheCure
 * @category    RulesAccessor
 * @category    Spec
 * @category    Test
 * @copyright   Gignite, 2012
 * @license     MIT
 *
 * @group  specs
 * @group  rulesaccessor
 */
use TheCure\Accessors\RulesAccessor;

use TheCure\Lists\AttributeList;

use TheCure\Attributes\Field;

class RulesAccessorTest extends \PHPUnit_Framework_TestCase {

	public function provideItShouldReturnRulesCalls()
	{
		$name_rules = array(
			array('not_empty'),
		);

		$date_rules = array(
			array('not_empty'),
			array('date'),
		);

		$attributes = new AttributeList(
			new Field('name', array('rules' => $name_rules)),
			new Field('date', array('rules' => $date_rules)));

		$rules_accessor = new RulesAccessor;

		return array(
			array(
				$rules_accessor->get($attributes, array('name', 'date')),
				array(
					'name' =>$name_rules,
					'date' => $date_rules,
				)
			),
			array(
				$rules_accessor->get($attributes, array('name')),
				array(
					'name' =>$name_rules,
				)
			),
			array(
				$rules_accessor->get($attributes, array('date')),
				array(
					'date' => $date_rules,
				)
			),
		);
	}

	/**
	 * @dataProvider  provideItShouldReturnRulesCalls
	 */
	public function testItShouldReturnRules($expected, $actual)
	{
		$this->assertSame($expected, $actual);
	}

}
